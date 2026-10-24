<?php

namespace App\Services;

use App\Models\Contrat;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FichePaieService
{
    protected array $taux = [
        'cnaps_salarie'   => 0.01,
        'cnaps_employeur' => 0.13,
        'ostie_salarie'   => 0.01,
        'ostie_employeur' => 0.05,
        'irsa_taux'       => 0.20,
    ];

    protected string $vue = 'gestion.contrats.fiche-paie';

    public function generer(Contrat $contrat, int $mois, int $annee): DomPdfInstance
    {
        // 🔍 Log étape 1 : chargement
        Log::info("[FichePaie] Début génération pour contrat {$contrat->numero} - {$mois}/{$annee}");

        try {
            $contrat->load(['employe.departement', 'employe.poste']);
        } catch (\Throwable $e) {
            Log::error("[FichePaie] Erreur chargement relations : " . $e->getMessage());
            throw $e;
        }

        $employe = $contrat->employe;

        if (!$employe) {
            throw new \Exception("Impossible de générer la fiche de paie : aucun employé associé au contrat.");
        }

        // 🔍 Log étape 2 : calculs
        try {
            $donnees = $this->calculerPaie($contrat, $mois, $annee);
            Log::info("[FichePaie] Calculs OK", ['net' => $donnees['salaireNet']]);
        } catch (\Throwable $e) {
            Log::error("[FichePaie] Erreur calculs : " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        // 🔍 Log étape 3 : rendu PDF
        try {
            /** @var DomPdfInstance $pdf */
            $pdf = Pdf::loadView($this->vue, array_merge($donnees, [
                    'contrat' => $contrat,
                    'employe' => $employe,
                ]))
                ->setPaper('A4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setOption('dpi', 150)
                ->setOption('enable_font_subsetting', true);

            // Force le rendu pour détecter les erreurs DomPDF
            $pdf->output();

            Log::info("[FichePaie] PDF généré avec succès pour contrat {$contrat->numero}");

            return $pdf;

        } catch (\Throwable $e) {
            Log::error("[FichePaie] Erreur rendu PDF : " . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // 🔍 propage l'erreur réelle pour debug
        }
    }

    public function download(Contrat $contrat, int $mois, int $annee)
    {
        /** @var DomPdfInstance $pdf */
        $pdf = $this->generer($contrat, $mois, $annee);

        return $pdf->download($this->getNomFichier($contrat, $mois, $annee));
    }

    public function getNomFichier(Contrat $contrat, int $mois, int $annee): string
    {
        $nomEmploye = str_replace(' ', '-', $contrat->employe?->nom_complet ?? 'employe');
        $periode    = sprintf('%04d-%02d', $annee, $mois);

        return "fiche-paie-{$periode}-{$contrat->numero}-{$nomEmploye}.pdf";
    }

    protected function calculerPaie(Contrat $contrat, int $mois, int $annee): array
    {
        $salaireBase = (float) $contrat->salaire;

        $ancienneteAnnees = $this->getAncienneteAnnees($contrat);
        $tauxAnciennete   = min($ancienneteAnnees * 0.01, 0.20);
        $primeAnciennete  = round($salaireBase * $tauxAnciennete, 2);

        $primeTransport = 100000;
        $primeLogement  = 50000;

        $salaireBrut = $salaireBase + $primeAnciennete + $primeTransport + $primeLogement;

        $cnapsSalarie = round($salaireBrut * $this->taux['cnaps_salarie'], 2);
        $ostieSalarie = round($salaireBrut * $this->taux['ostie_salarie'], 2);
        $totalCotisationsSalarie = $cnapsSalarie + $ostieSalarie;

        $baseImposable = $salaireBrut - $totalCotisationsSalarie;
        $irsa = round($baseImposable * $this->taux['irsa_taux'], 2);
        $totalRetenues = $totalCotisationsSalarie + $irsa;

        $salaireNet = $salaireBrut - $totalRetenues;

        $cnapsEmployeur = round($salaireBrut * $this->taux['cnaps_employeur'], 2);
        $ostieEmployeur = round($salaireBrut * $this->taux['ostie_employeur'], 2);
        $totalCotisationsPatronales = $cnapsEmployeur + $ostieEmployeur;

        $coutTotal = $salaireBrut + $totalCotisationsPatronales;

        $moisNoms = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
        ];

        return [
            'mois'                       => $mois,
            'annee'                      => $annee,
            'moisLabel'                  => $moisNoms[$mois] ?? 'Inconnu',
            'periodeLabel'               => sprintf('%s %d', $moisNoms[$mois] ?? '', $annee),
            'salaireBase'                => $salaireBase,
            'ancienneteAnnees'           => $ancienneteAnnees,
            'tauxAnciennete'             => $tauxAnciennete,
            'primeAnciennete'            => $primeAnciennete,
            'primeTransport'             => $primeTransport,
            'primeLogement'              => $primeLogement,
            'salaireBrut'                => $salaireBrut,
            'cnapsSalarie'               => $cnapsSalarie,
            'ostieSalarie'               => $ostieSalarie,
            'totalCotisationsSalarie'    => $totalCotisationsSalarie,
            'baseImposable'              => $baseImposable,
            'irsa'                       => $irsa,
            'totalRetenues'              => $totalRetenues,
            'salaireNet'                 => $salaireNet,
            'cnapsEmployeur'             => $cnapsEmployeur,
            'ostieEmployeur'             => $ostieEmployeur,
            'totalCotisationsPatronales' => $totalCotisationsPatronales,
            'coutTotal'                  => $coutTotal,
        ];
    }

    protected function getAncienneteAnnees(Contrat $contrat): int
    {
        if (!$contrat->date_debut) {
            return 0;
        }

        try {
            return Carbon::parse($contrat->date_debut)->diffInYears(now());
        } catch (\Throwable $e) {
            Log::warning("[FichePaie] Erreur calcul ancienneté : " . $e->getMessage());
            return 0;
        }
    }
}