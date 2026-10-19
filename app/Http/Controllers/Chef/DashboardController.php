<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Récupérer l'employé connecté et son département.
     */
    private function getChefContext(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [null, null];
        }

        $employe = Employe::where('matricule', $user->matricule)
            ->with('departement')
            ->first();

        $departement = $employe?->departement;

        return [$employe, $departement];
    }

    /**
     * Afficher le tableau de bord du chef.
     */
    public function index(): View
    {
        [$employe, $departement] = $this->getChefContext();

        // Si aucun département n'est associé au chef.
        if (!$departement) {
            return view('chef.dashboard', [
                'employe' => $employe,
                'departement' => null,
                'stats' => [
                    'equipe' => 0,
                    'presents_today' => 0,
                    'absents_today' => 0,
                    'conges_a_valider' => 0,
                    'taux_presence' => 0,
                ],
                'congesEnAttente' => collect(),
                'presencesJour' => collect(),
            ]);
        }

        // Récupérer les employés du département.
        $employeIds = Employe::where(
            'departement_id',
            $departement->id
        )->pluck('id');

        // Dates utilisées pour les recherches.
        $today = Carbon::today()->toDateString();

        $debutMois = Carbon::now()
            ->startOfMonth()
            ->toDateString();

        $finMois = Carbon::now()
            ->endOfMonth()
            ->toDateString();

        // ==========================================
        // STATISTIQUES DU JOUR
        // ==========================================

        $presentsToday = Presence::whereIn(
            'employe_id',
            $employeIds
        )
            ->where('date', $today)
            ->where('statut', 'present')
            ->count();

        $absentsToday = Presence::whereIn(
            'employe_id',
            $employeIds
        )
            ->where('date', $today)
            ->where('statut', 'absent')
            ->count();

        $congesAValider = DemandeConge::whereIn(
            'employe_id',
            $employeIds
        )
            ->where('statut', 'en_attente')
            ->count();

        $stats = [
            'equipe' => $employeIds->count(),
            'presents_today' => $presentsToday,
            'absents_today' => $absentsToday,
            'conges_a_valider' => $congesAValider,
            'taux_presence' => 0,
        ];

        // ==========================================
        // TAUX DE PRÉSENCE DU MOIS
        // ==========================================

        $totalPresencesMois = Presence::whereIn(
            'employe_id',
            $employeIds
        )
            ->whereBetween('date', [$debutMois, $finMois])
            ->count();

        $presencesOK = Presence::whereIn(
            'employe_id',
            $employeIds
        )
            ->whereBetween('date', [$debutMois, $finMois])
            ->where('statut', 'present')
            ->count();

        $stats['taux_presence'] = $totalPresencesMois > 0
            ? round(($presencesOK / $totalPresencesMois) * 100)
            : 0;

        // ==========================================
        // CONGÉS EN ATTENTE
        // ==========================================

        $congesEnAttente = DemandeConge::with([
            'employe',
            'typeConge',
        ])
            ->whereIn('employe_id', $employeIds)
            ->where('statut', 'en_attente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ==========================================
        // PRÉSENCES DU JOUR
        // ==========================================

        $presencesJour = Presence::with('employe')
            ->whereIn('employe_id', $employeIds)
            ->where('date', $today)
            ->orderByDesc('created_at')
            ->get();

        // Afficher le dashboard.
        return view('chef.dashboard', compact(
            'employe',
            'departement',
            'stats',
            'congesEnAttente',
            'presencesJour'
        ));
    }
}
