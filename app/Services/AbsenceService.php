<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\JourFerie;
use App\Models\Pointage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * AbsenceService
 * --------------
 * Détection automatique des absences.
 */
class AbsenceService
{
    /**
     * Détecte les absences pour une date donnée.
     */
    public function detecterAbsences(?string $date = null): array
    {
        $date = $date ?? now()->toDateString();
        $carbonDate = Carbon::parse($date);

        // 1. Vérifier jour ouvré
        if ($this->estJourNonOuvre($carbonDate)) {
            Log::info("Détection absences ignorée : {$date} n'est pas un jour ouvré.");
            return [
                'crees'   => 0,
                'ignores' => 0,
                'details' => ['message' => 'Jour non ouvré (week-end ou férié).'],
            ];
        }

        // 2. Employés actifs
        $employes = Employe::where('statut', 'actif')->get();
        $crees = 0;
        $ignores = 0;
        $details = [];

        // 3. Traiter chaque employé
        foreach ($employes as $employe) {

            // a) A pointé ?
            $aPointe = Pointage::where('employe_id', $employe->id)
                ->whereDate('date_heure', $date)
                ->where('type', 'entree')
                ->exists();
            if ($aPointe) { $ignores++; continue; }

            // b) En congé approuvé ?
            $aConge = DemandeConge::where('employe_id', $employe->id)
                ->where('statut', 'approuve')
                ->whereDate('date_debut', '<=', $date)
                ->whereDate('date_fin', '>=', $date)
                ->exists();
            if ($aConge) {
                $ignores++;
                $details[] = "{$employe->nom_complet} : en congé";
                continue;
            }

            // c) Absence déjà enregistrée ?
            $dejaAbsent = Absence::where('employe_id', $employe->id)
                ->whereDate('date_debut', '<=', $date)
                ->whereDate('date_fin', '>=', $date)
                ->exists();
            if ($dejaAbsent) {
                $ignores++;
                $details[] = "{$employe->nom_complet} : déjà enregistré";
                continue;
            }

            // d) Créer absence
            try {
                Absence::create([
                    'employe_id'   => $employe->id,
                    'date_debut'   => $date,
                    'date_fin'     => $date,
                    'nombre_jours' => 1,
                    'motif'        => 'Absence à vérifier',
                    'description'  => 'Aucun pointage détecté. Vérification RH nécessaire.',
                    'statut'       => 'en_attente',
                    'origine'      => 'auto',
                ]);

                $crees++;
                $details[] = "{$employe->nom_complet} : absence créée";
            } catch (\Exception $e) {
                Log::error("Erreur création absence pour {$employe->nom_complet} : " . $e->getMessage());
                $details[] = "{$employe->nom_complet} : ERREUR";
            }
        }

        // 4. Log
        Log::info("Détection absences {$date} : {$crees} créée(s), {$ignores} ignorée(s).");

        return [
            'crees'   => $crees,
            'ignores' => $ignores,
            'details' => $details,
        ];
    }

    /**
     * Vérifie si un jour est non ouvré.
     */
    private function estJourNonOuvre(Carbon $date): bool
    {
        if (in_array($date->dayOfWeek, [0, 6])) {
            return true;
        }

        return JourFerie::whereDate('date', $date->toDateString())
            ->where('est_chome', true)
            ->exists();
    }

    /**
     * Détecte sur une plage de dates.
     */
    public function detecterPlage(string $dateDebut, string $dateFin): array
    {
        $debut = Carbon::parse($dateDebut);
        $fin   = Carbon::parse($dateFin);
        $total = ['crees' => 0, 'ignores' => 0, 'jours' => []];

        while ($debut->lte($fin)) {
            $date = $debut->toDateString();
            $resultat = $this->detecterAbsences($date);

            $total['crees']   += $resultat['crees'];
            $total['ignores'] += $resultat['ignores'];
            $total['jours'][] = [
                'date'    => $date,
                'crees'   => $resultat['crees'],
                'ignores' => $resultat['ignores'],
            ];

            $debut->addDay();
        }

        return $total;
    }
}