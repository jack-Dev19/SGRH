<?php

namespace App\Services;

use App\Models\HistoriqueAction;
use App\Models\Pointage;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointageService
{
    public const HEURE_LIMITE_ARRIVEE = '08:15:00';
    public const HEURE_MIN_DEPART = '16:45:00';
    public const HEURES_PREVUES = 8;

    /**
     * Analyse arrivée et départ.
     */
    public function analyser(
        string $heureEntree,
        ?string $heureSortie = null
    ): array {

        $entree = Carbon::parse($heureEntree);

        $limite = Carbon::parse(
            $entree->format('Y-m-d') . ' ' .
            self::HEURE_LIMITE_ARRIVEE
        );

        $resultat = [
            'retard' => $entree->gt($limite),
            'minutes_retard' => $entree->gt($limite)
                ? (int) $limite->diffInMinutes($entree)
                : 0,

            'depart_anticipe' => false,
            'minutes_avance' => 0,
            'heures_travaillees' => 0,
        ];

        if ($heureSortie) {

            $sortie = Carbon::parse($heureSortie);

            $minDepart = Carbon::parse(
                $sortie->format('Y-m-d') . ' ' .
                self::HEURE_MIN_DEPART
            );

            $resultat['depart_anticipe'] = $sortie->lt($minDepart);

            $resultat['minutes_avance'] = $sortie->lt($minDepart)
                ? (int) $sortie->diffInMinutes($minDepart)
                : 0;

            $resultat['heures_travaillees'] = round(
                $entree->diffInMinutes($sortie) / 60,
                2
            );
        }

        return $resultat;
    }


    /**
     * Détecter les anomalies d'une présence saisie manuellement.
     */
    public function analyserPresenceManuelle(Presence $presence): void
    {
        // Ne pas traiter les présences issues du QR Code.
        if ($presence->source !== 'manuel') {
            return;
        }
    
        $descriptions = [];
    
        // Un congé ne constitue pas une anomalie.
        if ($presence->statut === 'conge') {
            $presence->update([
                'est_anomalie' => false,
                'description_anomalie' => null,
            ]);
    
            return;
        }
    
        // Détecter une absence.
        if ($presence->statut === 'absent') {
            $descriptions[] = 'Absence enregistrée.';
        }
    
        // Vérifier l'heure d'arrivée.
        if ($presence->heure_entree) {
            $date = $presence->date->format('Y-m-d');
    
            $entree = Carbon::parse(
                $date . ' ' . $presence->heure_entree
            );
    
            $limite = Carbon::parse(
                $date . ' ' . self::HEURE_LIMITE_ARRIVEE
            );
    
            if ($entree->gt($limite)) {
                $minutes = (int) $limite->diffInMinutes($entree);
    
                $descriptions[] = "Retard de {$minutes} minute(s).";
            } elseif ($presence->statut === 'retard') {
                $minutes = $presence->retard_minutes ?? 0;
    
                $descriptions[] = "Retard déclaré de {$minutes} minute(s).";
            }
        } elseif ($presence->statut === 'retard') {
            $minutes = $presence->retard_minutes ?? 0;
    
            $descriptions[] = "Retard déclaré de {$minutes} minute(s).";
        }
    
        // Vérifier un départ anticipé.
        if ($presence->heure_sortie) {
            $date = $presence->date->format('Y-m-d');
    
            $sortie = Carbon::parse(
                $date . ' ' . $presence->heure_sortie
            );
    
            $limiteDepart = Carbon::parse(
                $date . ' ' . self::HEURE_MIN_DEPART
            );
    
            if ($sortie->lt($limiteDepart)) {
                $minutes = (int) $sortie->diffInMinutes($limiteDepart);
    
                $descriptions[] = "Départ anticipé de {$minutes} minute(s).";
            }
        }
    
        // Enregistrer le résultat de l'analyse.
        $presence->update([
            'est_anomalie' => !empty($descriptions),
            'description_anomalie' => !empty($descriptions)
                ? implode(' ', $descriptions)
                : null,
        ]);
    }

    /**
     * Enregistrer une anomalie sur un pointage.
     */
    public function marquerAnomalie(
        Pointage $pointage,
        string $description
    ): void {

        $pointage->update([
            'est_anomalie' => true,
            'description_anomalie' => $description,
        ]);
    }

    /**
     * Recalculer la présence.
     */
    public function mettreAJourPresence(string $employeId): void
    {
        $today = now()->toDateString();

        $entree = Pointage::where('employe_id', $employeId)
            ->whereDate('date_heure', $today)
            ->where('type', 'entree')
            ->orderBy('date_heure')
            ->first();

        $sortie = Pointage::where('employe_id', $employeId)
            ->whereDate('date_heure', $today)
            ->where('type', 'sortie')
            ->orderByDesc('date_heure')
            ->first();

        if (!$entree) {
            return;
        }

        $analyse = $this->analyser(
            $entree->date_heure->format('Y-m-d H:i:s'),
            $sortie?->date_heure->format('Y-m-d H:i:s')
        );

        Presence::updateOrCreate(
            [
                'employe_id' => $employeId,
                'date' => $today,
            ],
            [
                'heure_entree' => $entree->date_heure->format('H:i:s'),
                'heure_sortie' => $sortie?->date_heure->format('H:i:s'),
                'heures_travaillees' => $analyse['heures_travaillees'],
                'retard_minutes' => $analyse['minutes_retard'],
                'statut' => $analyse['retard'] ? 'retard' : 'present',
                'source' => 'qr_code',
                'commentaire' => $analyse['retard']
                    ? 'Retard de ' . $analyse['minutes_retard'] . ' minutes'
                    : null,
            ]
        );
    }

    
    /**
     * Détecter les anomalies QR et les anomalies des présences manuelles.
     */
    public function detecterAnomalies(?string $date = null): array
    {
        $date = $date ?? now()->toDateString();
    
        // Récupérer les anomalies issues des pointages QR.
        $anomaliesQr = Pointage::with('employe')
            ->whereDate('date_heure', $date)
            ->where('est_anomalie', true)
            ->orderByDesc('date_heure')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'employe' => $p->employe?->nom_complet ?? 'Inconnu',
                    'matricule' => $p->employe?->matricule ?? 'N/A',
                    'type' => $p->type,
                    'source' => 'qr_code',
                    'heure' => $p->date_heure?->format('H:i') ?? '--:--',
                    'description' => $p->description_anomalie
                        ?? 'Anomalie non précisée',
                ];
            });
    
        // Récupérer les anomalies issues des présences manuelles.
        $anomaliesManuelles = Presence::with('employe')
            ->whereDate('date', $date)
            ->where('source', 'manuel')
            ->where('est_anomalie', true)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'employe' => $p->employe?->nom_complet ?? 'Inconnu',
                    'matricule' => $p->employe?->matricule ?? 'N/A',
                    'type' => 'manuel',
                    'source' => 'manuel',
                    'heure' => $p->heure_entree
                        ? substr($p->heure_entree, 0, 5)
                        : ($p->heure_sortie
                            ? substr($p->heure_sortie, 0, 5)
                            : '--:--'),
                    'description' => $p->description_anomalie
                        ?? 'Anomalie non précisée',
                ];
            });
    
        // Fusionner les deux sources et trier par heure décroissante.
        return $anomaliesQr
            ->concat($anomaliesManuelles)
            ->sortByDesc('heure')
            ->values()
            ->all();
    }

    /**
     * Corriger un pointage avec historique.
     */
    public function corriger(
        Pointage $pointage,
        array $nouvellesDonnees,
        string $raison
    ): void {

        DB::transaction(function () use (
            $pointage,
            $nouvellesDonnees,
            $raison
        ) {

            $ancienneValeur = [
                'date_heure' => $pointage->date_heure?->format('Y-m-d H:i:s'),
                'type' => $pointage->type,
                'est_anomalie' => $pointage->est_anomalie,
                'description_anomalie' => $pointage->description_anomalie,
            ];

            $pointage->update([
                'date_heure' => $nouvellesDonnees['date_heure'],
                'type' => $nouvellesDonnees['type'],
                'est_anomalie' => false,
                'description_anomalie' => null,
                'type_scan' => 'manuel',
                'commentaire' => 'Corrigé par RH : ' . $raison,
            ]);

            HistoriqueAction::create([
                'user_id' => auth()->id(),
                'action' => 'modification',
                'module' => 'pointages',
                'description' => "Correction du pointage : {$raison}",
                'ancienne_valeur' => json_encode($ancienneValeur),
                'nouvelle_valeur' => json_encode($nouvellesDonnees),
            ]);

            $this->mettreAJourPresence($pointage->employe_id);
        });
    }
}
