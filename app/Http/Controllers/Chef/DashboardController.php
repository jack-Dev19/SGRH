<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * DashboardController (Chef)
 * --------------------------
 * Tableau de bord du chef de département.
 * Le chef ne voit QUE son département.
 */
class DashboardController extends Controller
{
    /**
     * Récupère l'employé connecté + son département.
     */
    private function getChefContext(): array
    {
        $employe = Employe::where('user_id', Auth::id())
            ->with('departement')
            ->first();

        $departement = $employe?->departement;

        return [$employe, $departement];
    }

    public function index(): View
    {
        [$employe, $departement] = $this->getChefContext();

        if (!$departement) {
            return view('chef.dashboard', ['departement' => null]);
        }

        $employeIds = Employe::where('departement_id', $departement->id)->pluck('id');
        $today      = now()->toDateString();

        // Stats
        $stats = [
            'equipe'           => $employeIds->count(),
            'presents_today'   => Presence::whereIn('employe_id', $employeIds)
                ->whereDate('date', $today)
                ->where('statut', 'present')
                ->count(),
            'absents_today'    => Presence::whereIn('employe_id', $employeIds)
                ->whereDate('date', $today)
                ->where('statut', 'absent')
                ->count(),
            'conges_a_valider' => DemandeConge::whereIn('employe_id', $employeIds)
                ->where('statut', 'en_attente')
                ->count(),
        ];

        // Taux de présence (mois en cours)
        $totalPresencesMois = Presence::whereIn('employe_id', $employeIds)
            ->whereMonth('date', now()->month)
            ->count();

        $presencesOK = Presence::whereIn('employe_id', $employeIds)
            ->whereMonth('date', now()->month)
            ->where('statut', 'present')
            ->count();

        $stats['taux_presence'] = $totalPresencesMois > 0
            ? round(($presencesOK / $totalPresencesMois) * 100)
            : 0;

        // Congés en attente (5 plus récents)
        $congesEnAttente = DemandeConge::with(['employe', 'typeConge'])
            ->whereIn('employe_id', $employeIds)
            ->where('statut', 'en_attente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Présences du jour
        $presencesJour = Presence::with('employe')
            ->whereIn('employe_id', $employeIds)
            ->whereDate('date', $today)
            ->orderByDesc('created_at')
            ->get();

        return view('chef.dashboard', compact(
            'employe',
            'departement',
            'stats',
            'congesEnAttente',
            'presencesJour'
        ));
    }
}