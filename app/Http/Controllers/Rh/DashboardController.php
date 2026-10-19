<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\OffreEmploi;
use App\Models\Salaire;
use Illuminate\View\View;

/**
 * DashboardController (RH)
 * ------------------------
 * Tableau de bord du Responsable RH.
 * Mêmes statistiques que l'Admin.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        // ==========================================================
        // 1. STATISTIQUES
        // ==========================================================
        $stats = [
            'total_employes'    => Employe::count(),
            'employes_actifs'   => Employe::where('statut', 'actif')->count(),
            'conges_en_cours'   => DemandeConge::where('statut', 'approuve')->count(),
            'conges_en_attente' => DemandeConge::where('statut', 'en_attente')->count(),
            'recrutements'      => OffreEmploi::where('statut', 'publiee')->count(),
        ];

        $masseSalariale = Salaire::where('statut', 'actif')
            ->sum('salaire_base');

        // ==========================================================
        // 2. RÉPARTITION PAR DÉPARTEMENT
        // ==========================================================
        $repartitionDepartements = Departement::withCount('employes')
            ->orderByDesc('employes_count')
            ->get();

        // ==========================================================
        // 3. CONGÉS PAR MOIS
        // ==========================================================
        $congesParMois = DemandeConge::selectRaw(
            'MONTH(date_debut) as mois, COUNT(*) as total'
        )
            ->whereYear('date_debut', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $congesParMoisComplet = [];

        for ($m = 1; $m <= 12; $m++) {
            $congesParMoisComplet[$m] = $congesParMois[$m] ?? 0;
        }

        // ==========================================================
        // 4. ÉVOLUTION DE L'EFFECTIF
        // ==========================================================
        $embauchesParMois = Employe::selectRaw(
            'MONTH(date_embauche) as mois, COUNT(*) as total'
        )
            ->whereYear('date_embauche', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $effectifParMois = [];

        $cumul = Employe::whereYear(
            'date_embauche',
            '<',
            now()->year
        )->count();

        for ($m = 1; $m <= 12; $m++) {
            $cumul += $embauchesParMois[$m] ?? 0;
            $effectifParMois[$m] = $cumul;
        }

        // ==========================================================
        // 5. CONGÉS EN ATTENTE
        // ==========================================================
        $congesEnAttente = DemandeConge::with([
            'employe',
            'typeConge'
        ])
            ->where('statut', 'en_attente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ==========================================================
        // 6. ACTIVITÉ RÉCENTE
        // ==========================================================
        $activiteRecente = DemandeConge::with('employe')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($conge) {
                return [
                    'date'  => $conge->created_at,
                    'titre' => 'Demande de congé ' . $conge->statut,
                    'user'  => $conge->employe?->nom_complet
                        ?? 'Employé inconnu',
                    'type'  => 'conge',
                ];
            });

        // ==========================================================
        // 7. NOTIFICATIONS NON LUES
        // ==========================================================
        // Le système de notifications n'est pas utilisé.
        $notificationsNonLues = 0;

        return view('rh.dashboard', compact(
            'stats',
            'masseSalariale',
            'repartitionDepartements',
            'congesParMoisComplet',
            'effectifParMois',
            'congesEnAttente',
            'activiteRecente',
            'notificationsNonLues'
        ));
    }
}
