<?php

namespace App\Services;

use App\Models\HistoriqueAction;
use App\Models\Pointage;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PointageService
 * ---------------
 * Règles métier pour les pointages :
 * - Détection des retards / départs anticipés
 * - Calcul des heures travaillées
 * - Correction des pointages (avec audit)
 * - Mise à jour de la table `presences`
 * - Détection des anomalies
 */
class PointageService
{
    /** Heure limite d'arrivée (au-delà = retard). */
    public const HEURE_LIMITE_ARRIVEE = '08:15:00';

    /** Heure minimale de départ (avant = départ anticipé). */
    public const HEURE_MIN_DEPART = '16:45:00';

    /** Heures prévues par jour. */
    public const HEURES_PREVUES = 8;

    // =================================================================
    // ANALYSE
    // =================================================================

    /**
     * Analyse un pointage et retourne les anomalies détectées.
     *
     * @return array{
     *     retard: bool,
     *     minutes_retard: int,
     *     depart_anticipe: bool,
     *     minutes_avance: int,
     *     heures_travaillees: float
     * }
     */
    public function analyser(string $heureEntree, ?string $heureSortie = null): array
    {
        $resultat = [
            'retard'             => false,
            'minutes_retard'     => 0,
            'depart_anticipe'    => false,
            'minutes_avance'     => 0,
            'heures_travaillees' => 0,
        ];

        // --- Analyse du retard (arrivée) ---
        $entree = Carbon::parse($heureEntree);
        $limite = Carbon::parse(self::HEURE_LIMITE_ARRIVEE);

        if ($entree->gt($limite)) {
            $resultat['retard']         = true;
            // ⭐ abs() pour garantir une valeur positive
            $resultat['minutes_retard'] = (int) abs($entree->diffInMinutes($limite));
        }

        // --- Analyse du départ (si présent) ---
        if ($heureSortie) {
            $sortie    = Carbon::parse($heureSortie);
            $minDepart = Carbon::parse(self::HEURE_MIN_DEPART);

            if ($sortie->lt($minDepart)) {
                $resultat['depart_anticipe'] = true;
                // ⭐ abs() pour garantir une valeur positive
                $resultat['minutes_avance']  = (int) abs($sortie->diffInMinutes($minDepart));
            }

            // Heures travaillées (⭐ abs pour garantir la positivité)
            $resultat['heures_travaillees'] = round(abs($entree->diffInMinutes($sortie)) / 60, 2);
        }

        return $resultat;
    }

    // =================================================================
    // MISE À JOUR DE LA TABLE PRESENCES
    // =================================================================

    /**
     * Recalcule la table `presences` pour un employé (aujourd'hui).
     */
    public function mettreAJourPresence(string $employeId): void
    {
        $today = now()->toDateString();

        // Récupérer le 1er pointage d'entrée
        $entree = Pointage::where('employe_id', $employeId)
            ->whereDate('date_heure', $today)
            ->where('type', 'entree')
            ->orderBy('date_heure')
            ->first();

        // Récupérer le dernier pointage de sortie
        $sortie = Pointage::where('employe_id', $employeId)
            ->whereDate('date_heure', $today)
            ->where('type', 'sortie')
            ->orderByDesc('date_heure')
            ->first();

        if (!$entree) {
            // Pas d'entrée → on ne crée pas de présence
            return;
        }

        // Analyser
        $analyse = $this->analyser(
            $entree->date_heure->format('H:i:s'),
            $sortie?->date_heure->format('H:i:s')
        );

        // ⭐ Sécurité : forcer les valeurs positives
        $minutesRetard = max(0, (int) ($analyse['minutes_retard'] ?? 0));
        $heuresTravaillees = max(0, (float) ($analyse['heures_travaillees'] ?? 0));

        // Créer ou mettre à jour la présence
        Presence::updateOrCreate(
            [
                'employe_id' => $employeId,
                'date'       => $today,
            ],
            [
                'heure_entree'       => $entree->date_heure->format('H:i:s'),
                'heure_sortie'       => $sortie?->date_heure->format('H:i:s'),
                'heures_travaillees' => $heuresTravaillees,
                'retard_minutes'     => $minutesRetard,
                'statut'             => $analyse['retard'] ? 'retard' : 'present',
                'source'             => 'qr_code',
                'commentaire'        => $analyse['retard']
                    ? "Retard {$minutesRetard} min"
                    : null,
            ]
        );
    }

    // =================================================================
    // CORRECTION D'UN POINTAGE (avec audit)
    // =================================================================

    /**
     * Corrige un pointage existant (avec traçabilité).
     */
    public function corriger(Pointage $pointage, array $nouvellesDonnees, string $raison): void
    {
        DB::transaction(function () use ($pointage, $nouvellesDonnees, $raison) {

            // Sauvegarder l'ancienne valeur (audit)
            $ancienneValeur = [
                'date_heure'  => $pointage->date_heure?->format('Y-m-d H:i:s'),
                'type'        => $pointage->type,
                'commentaire' => $pointage->commentaire,
            ];

            // Appliquer la modification
            $pointage->update([
                'date_heure'           => $nouvellesDonnees['date_heure'],
                'type'                 => $nouvellesDonnees['type'],
                'est_anomalie'         => $nouvellesDonnees['est_anomalie'] ?? false,
                'description_anomalie' => $nouvellesDonnees['description_anomalie'] ?? null,
                'commentaire'          => "Corrigé par RH : {$raison}",
                'type_scan'            => 'manuel',
            ]);

            // Log d'audit
            HistoriqueAction::create([
                'user_id'         => auth()->id(),
                'action'          => 'modification',
                'module'          => 'pointages',
                'description'     => "Correction du pointage #{$pointage->id} : {$raison}",
                'ancienne_valeur' => json_encode($ancienneValeur),
                'nouvelle_valeur' => json_encode($nouvellesDonnees),
            ]);

            // Mettre à jour la table presences
            $this->mettreAJourPresence($pointage->employe_id);
        });
    }

    // =================================================================
    // DÉTECTION DES ANOMALIES
    // =================================================================

    /**
     * Détecte et liste les anomalies de la journée.
     */
    public function detecterAnomalies(?string $date = null): array
    {
        $date = $date ?? now()->toDateString();

        $anomalies = Pointage::with('employe')
            ->whereDate('date_heure', $date)
            ->where('est_anomalie', true)
            ->orderByDesc('date_heure')
            ->get();

        return $anomalies->map(function ($p) {
            return [
                'id'          => $p->id,
                'employe'     => $p->employe?->nom_complet,
                'matricule'   => $p->employe?->matricule,
                'type'        => $p->type,
                'heure'       => $p->date_heure?->format('H:i'),
                'description' => $p->description_anomalie,
            ];
        })->toArray();
    }

    // =================================================================
    // HELPERS
    // =================================================================

    /**
     * Vérifie si un employé a déjà pointé aujourd'hui.
     */
    public function aDejaPointe(string $employeId, string $type, ?string $date = null): bool
    {
        $date = $date ?? now()->toDateString();

        return Pointage::where('employe_id', $employeId)
            ->whereDate('date_heure', $date)
            ->where('type', $type)
            ->exists();
    }

    /**
     * Détermine le type de pointage à effectuer (entree ou sortie).
     */
    public function determinerType(string $employeId): string
    {
        $aEntree = $this->aDejaPointe($employeId, 'entree');
        $aSortie = $this->aDejaPointe($employeId, 'sortie');

        if ($aEntree && $aSortie) {
            throw new \Exception('Employé a déjà pointé son arrivée ET son départ aujourd\'hui.');
        }

        return $aEntree ? 'sortie' : 'entree';
    }
}