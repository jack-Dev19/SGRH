<?php

namespace App\Services;

use App\Models\Contrat;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FichePaieService
{
    /**
     * Taux de cotisations et d'imposition.
     */
    protected array $taux = [
        'cnaps_salarie'   => 0.01,
        'cnaps_employeur' => 0.13,
        'ostie_salarie'   => 0.01,
        'ostie_employeur' => 0.05,
        'irsa_taux'       => 0.20,
    ];

    protected string $vue = 'gestion.contrats.fiche-paie';

    /**
     * Générer la fiche de paie PDF.
     */
    public function generer(
        Contrat $contrat,
        int $mois,
        int $annee
    ): DomPdfInstance {
        Log::info(
            "[FichePaie] Début génération pour contrat {$contrat->numero} - {$mois}/{$annee}"
        );

        try {
            $contrat->load(['employe.departement', 'employe.poste']);
        } catch (\Throwable $e) {
            Log::error(
                "[FichePaie] Erreur chargement relations : " . $e->getMessage()
            );

            throw $e;
        }

        $employe = $contrat->employe;

        if (!$employe) {
            throw new \Exception(
                "Impossible de générer la fiche de paie : aucun employé associé au contrat."
            );
        }

        // Calculer les montants de la fiche de paie en MGA.
        try {
            $donnees = $this->calculerPaie($contrat, $mois, $annee);

            Log::info("[FichePaie] Calculs OK", [
                'devise_contrat' => $contrat->devise,
                'salaire_base_mga' => $donnees['salaireBase'],
                'net' => $donnees['salaireNet'],
            ]);
        } catch (\Throwable $e) {
            Log::error(
                "[FichePaie] Erreur calculs : " . $e->getMessage(),
                ['trace' => $e->getTraceAsString()]
            );

            throw $e;
        }

        // Générer le document PDF.
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

            // Vérifier le rendu avant le téléchargement.
            $pdf->output();

            Log::info(
                "[FichePaie] PDF généré avec succès pour contrat {$contrat->numero}"
            );

            return $pdf;

        } catch (\Throwable $e) {
            Log::error(
                "[FichePaie] Erreur rendu PDF : " . $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw $e;
        }
    }

    /**
     * Télécharger la fiche de paie.
     */
    public function download(
        Contrat $contrat,
        int $mois,
        int $annee
    ) {
        /** @var DomPdfInstance $pdf */
        $pdf = $this->generer($contrat, $mois, $annee);

        return $pdf->download(
            $this->getNomFichier($contrat, $mois, $annee)
        );
    }

    /**
     * Construire le nom du fichier PDF.
     */
    public function getNomFichier(
        Contrat $contrat,
        int $mois,
        int $annee
    ): string {
        $nomEmploye = str_replace(
            ' ',
            '-',
            $contrat->employe?->nom_complet ?? 'employe'
        );

        $periode = sprintf('%04d-%02d', $annee, $mois);

        return "fiche-paie-{$periode}-{$contrat->numero}-{$nomEmploye}.pdf";
    }
    
    /**
     * Exposer les calculs de paie pour la création des bulletins.
     */
    public function calculerPourBulletin(
        Contrat $contrat,
        int $mois,
        int $annee
    ): array {
        $contrat->load(['employe.departement', 'employe.poste']);

        $dateReference = Carbon::create($annee, $mois, 1)->endOfMonth();

        $donnees = $this->calculerPaie(
            $contrat,
            $mois,
            $annee,
            $dateReference
        );

        return array_merge($donnees, [
            'contrat' => $contrat,
            'employe' => $contrat->employe,
        ]);
    }

    protected function calculerPaie(
        Contrat $contrat,
        int $mois,
        int $annee,
        ?Carbon $dateReference = null
    ): array {
        // Convertir le salaire du contrat en Ariary (MGA).
        $conversionService = app(ConversionDeviseService::class);

        $deviseContrat = strtoupper($contrat->devise ?? 'MGA');

        $salaireBase = $conversionService->convertirEntreDevises(
            (float) $contrat->salaire,
            $deviseContrat,
            'MGA'
        );

        // Arrondir le salaire converti à deux décimales.
        $salaireBase = round($salaireBase, 2);

        Log::info("[FichePaie] Conversion salaire en MGA", [
            'salaire_original' => $contrat->salaire,
            'devise_originale' => $deviseContrat,
            'salaire_mga' => $salaireBase,
        ]);

        
        // Déterminer si le contrat est une convention de stage.
        $estStage = mb_strtolower(trim((string) $contrat->type)) === 'stage';
        
        // Calcul de l'ancienneté.
        // Un stagiaire reçoit une gratification : on ne lui applique
        // pas automatiquement une prime d'ancienneté.
        $ancienneteAnnees = $estStage
            ? 0
            : $this->getAncienneteAnnees($contrat, $dateReference);
        
        $tauxAnciennete = $estStage
            ? 0
            : min($ancienneteAnnees * 0.01, 0.20);
        
        $primeAnciennete = round(
            $salaireBase * $tauxAnciennete,
            2
        );
        
        // Primes exprimées en Ariary.
        // Pour un stage, le montant du contrat représente la gratification.
        // Les primes fixes ne sont donc pas ajoutées automatiquement.
        $primeTransport = $estStage ? 0 : 100000;
        $primeLogement = $estStage ? 0 : 50000;
        
        // Salaire brut ou gratification brute.
        $salaireBrut = (
            $salaireBase
            + $primeAnciennete
            + $primeTransport
            + $primeLogement
        );
        
        // Cotisations salariales.
        // La convention de stage exclut explicitement la CNAPS et l'OSTIE.
        $cnapsSalarie = $estStage
            ? 0
            : round($salaireBrut * $this->taux['cnaps_salarie'], 2);
        
        $ostieSalarie = $estStage
            ? 0
            : round($salaireBrut * $this->taux['ostie_salarie'], 2);
        
        $totalCotisationsSalarie = (
            $cnapsSalarie + $ostieSalarie
        );


        // Calcul de l'impôt.
        $baseImposable = (
            $salaireBrut - $totalCotisationsSalarie
        );

        $irsa = round(
            $baseImposable * $this->taux['irsa_taux'],
            2
        );

        $totalRetenues = (
            $totalCotisationsSalarie + $irsa
        );

        // Salaire net.
        $salaireNet = (
            $salaireBrut - $totalRetenues
        );

        // Cotisations patronales.
        $cnapsEmployeur = round(
            $salaireBrut * $this->taux['cnaps_employeur'],
            2
        );

        $ostieEmployeur = round(
            $salaireBrut * $this->taux['ostie_employeur'],
            2
        );

        $totalCotisationsPatronales = (
            $cnapsEmployeur + $ostieEmployeur
        );

        $coutTotal = (
            $salaireBrut + $totalCotisationsPatronales
        );

        // Noms des mois.
        $moisNoms = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return [
            'mois' => $mois,
            'annee' => $annee,
            'moisLabel' => $moisNoms[$mois] ?? 'Inconnu',
            'periodeLabel' => sprintf(
                '%s %d',
                $moisNoms[$mois] ?? '',
                $annee
            ),

            'salaireBase' => $salaireBase,
            'ancienneteAnnees' => $ancienneteAnnees,
            'tauxAnciennete' => $tauxAnciennete,
            'primeAnciennete' => $primeAnciennete,
            'primeTransport' => $primeTransport,
            'primeLogement' => $primeLogement,
            'salaireBrut' => $salaireBrut,

            'cnapsSalarie' => $cnapsSalarie,
            'ostieSalarie' => $ostieSalarie,
            'totalCotisationsSalarie' => $totalCotisationsSalarie,

            'baseImposable' => $baseImposable,
            'irsa' => $irsa,
            'totalRetenues' => $totalRetenues,
            'salaireNet' => $salaireNet,

            'cnapsEmployeur' => $cnapsEmployeur,
            'ostieEmployeur' => $ostieEmployeur,
            'totalCotisationsPatronales' => $totalCotisationsPatronales,
            'coutTotal' => $coutTotal,
        ];
    }

    /**
     * Calculer l'ancienneté de l'employé.
     */
    protected function getAncienneteAnnees(
        Contrat $contrat,
        ?Carbon $dateReference = null
    ): int {
        if (!$contrat->date_debut) {
            return 0;
        }
    
        try {
            $dateReference = $dateReference ?? now();
    
            return Carbon::parse($contrat->date_debut)
                ->diffInYears($dateReference);
        } catch (\Throwable $e) {
            Log::warning(
                "[FichePaie] Erreur calcul ancienneté : " . $e->getMessage()
            );
    
            return 0;
        }
    }
}
