<?php

namespace App\Services;

use App\Models\BulletinPaie;
use App\Models\Contrat;
use App\Models\Prime;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BulletinPaieService
{
    public function __construct(
        protected FichePaieService $fichePaieService
    ) {}

    /**
     * Générer un bulletin en brouillon à partir d'un contrat.
     */
    public function generer(
        Contrat $contrat,
        int $mois,
        int $annee
    ): BulletinPaie {
        if ($mois < 1 || $mois > 12) {
            throw ValidationException::withMessages([
                'periode' => 'Le mois sélectionné est invalide.',
            ]);
        }

        if ($contrat->statut !== 'actif') {
            throw ValidationException::withMessages([
                'contrat' => 'Ce contrat n’est pas actif.',
            ]);
        }

        $debutMois = sprintf('%04d-%02d-01', $annee, $mois);
        $finMois = date('Y-m-t', strtotime($debutMois));

        if (
            $contrat->date_debut->format('Y-m-d') > $finMois ||
            ($contrat->date_fin && $contrat->date_fin->format('Y-m-d') < $debutMois)
        ) {
            throw ValidationException::withMessages([
                'contrat' => 'Ce contrat ne couvre pas la période sélectionnée.',
            ]);
        }

        $periode = $this->nomMois($mois);

        $existe = BulletinPaie::where('employe_id', $contrat->employe_id)
            ->where('periode', $periode)
            ->where('annee', $annee)
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'periode' => 'Un bulletin existe déjà pour cet employé et cette période.',
            ]);
        }

        $donnees = $this->fichePaieService->calculerPourBulletin(
            $contrat,
            $mois,
            $annee
        );

        $primesAccordees = Prime::where('employe_id', $contrat->employe_id)
            ->where('statut', 'accordee')
            ->whereBetween('date_prime', [$debutMois, $finMois])
            ->get();

        $totalPrimesVariables = round(
            $primesAccordees->sum('montant'),
            2
        );

        $totalPrimes = round(
            $donnees['primeAnciennete']
            + $donnees['primeTransport']
            + $donnees['primeLogement']
            + $totalPrimesVariables,
            2
        );

        $salaireBrut = round(
            $donnees['salaireBrut'] + $totalPrimesVariables,
            2
        );

        $salaireNet = round(
            $donnees['salaireNet'] + $totalPrimesVariables,
            2
        );

        return DB::transaction(function () use (
            $contrat,
            $annee,
            $periode,
            $donnees,
            $totalPrimes,
            $primesAccordees,
            $salaireBrut,
            $salaireNet
        ) {
            $bulletin = BulletinPaie::create([
                'employe_id' => $contrat->employe_id,
                'periode' => $periode,
                'annee' => $annee,
                'salaire_base' => $donnees['salaireBase'],
                'total_primes' => $totalPrimes,
                'total_heures_supplementaires' => 0,
                'total_retenues' => $donnees['totalRetenues'],
                'salaire_brut' => $salaireBrut,
                'salaire_net' => $salaireNet,
                'statut' => 'brouillon',
                'date_generation' => now()->toDateString(),
                'commentaire' => null,
            ]);

            $lignes = [
                ['prime', 'Prime d’ancienneté', $donnees['primeAnciennete']],
                ['prime', 'Prime de transport', $donnees['primeTransport']],
                ['prime', 'Prime de logement', $donnees['primeLogement']],
                ['retenue', 'Cotisation CNAPS salarié', $donnees['cnapsSalarie']],
                ['retenue', 'Cotisation OSTIE salarié', $donnees['ostieSalarie']],
                ['retenue', 'IRSA', $donnees['irsa']],
            ];

            foreach ($primesAccordees as $prime) {
                $lignes[] = [
                    'prime',
                    'Prime : ' . $prime->type_prime,
                    $prime->montant,
                ];
            }

            foreach ($lignes as [$type, $libelle, $montant]) {
                if ($montant > 0) {
                    $bulletin->lignes()->create([
                        'type' => $type,
                        'libelle' => $libelle,
                        'quantite' => 1,
                        'taux' => null,
                        'montant' => $montant,
                    ]);
                }
            }

            return $bulletin->load(['employe', 'lignes']);
        });
    }

    private function nomMois(int $mois): string
    {
        return [
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
        ][$mois];
    }
}
