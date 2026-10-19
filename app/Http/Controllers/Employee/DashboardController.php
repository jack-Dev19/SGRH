<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // ⭐ Chercher l'employé par matricule
        $employe = Employe::where('matricule', $user->matricule)
            ->with(['departement', 'poste', 'contrats'])
            ->first();

        if (!$employe) {
            return view('employee.dashboard', [
                'employe' => null,
                'stats'   => [
                    'presences_mois'   => 0,
                    'conges_attente'   => 0,
                    'conges_approuves' => 0,
                    'solde_conges'     => 0,
                ],
                'dernieresPresences' => collect(),
                'derniersConges'     => collect(),
                'contratActif'       => null,
            ]);
        }

        // Stats
        $stats = [
            'presences_mois'   => Presence::where('employe_id', $employe->id)
                ->whereMonth('date', now()->month)
                ->where('statut', 'present')
                ->count(),
            'conges_attente'   => DemandeConge::where('employe_id', $employe->id)
                ->where('statut', 'en_attente')
                ->count(),
            'conges_approuves' => DemandeConge::where('employe_id', $employe->id)
                ->where('statut', 'approuve')
                ->sum('nombre_jours'),
            'jours_conges_max' => 30,
        ];

        $stats['solde_conges'] = $stats['jours_conges_max'] - $stats['conges_approuves'];

        // Dernières présences
        $dernieresPresences = Presence::where('employe_id', $employe->id)
            ->orderByDesc('date')
            ->take(5)
            ->get();

        // Derniers congés
        $derniersConges = DemandeConge::with('typeConge')
            ->where('employe_id', $employe->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Contrat actif
        $contratActif = $employe->contrats()
            ->where('statut', 'actif')
            ->orderByDesc('date_debut')
            ->first();

        return view('employee.dashboard', compact(
            'employe',
            'stats',
            'dernieresPresences',
            'derniersConges',
            'contratActif'
        ));
    }
}