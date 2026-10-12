<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Departement;
use App\Models\Conge;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // =============================================
        // 1. STATISTIQUES GÉNÉRALES
        // =============================================
        $totalEmployees  = Employee::count();
        $activeEmployees = Employee::where('actif', true)->count();
        $pendingConges   = Conge::where('statut', 'en_attente')->count();
        $totalSalary     = Employee::where('actif', true)->sum('salaire_base');
        $recruitments    = 3;
        $notifications   = 2;
        $maleCount       = Employee::where('sexe', 'M')->count();
        $femaleCount     = Employee::where('sexe', 'F')->count();

        // =============================================
        // 2. GRAPHIQUE : Congés par mois
        // =============================================
        $congesParMois = Conge::select(
                DB::raw('MONTH(date_debut) as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('date_debut', now()->year)
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        $congesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $congesData[] = $congesParMois[$i] ?? 0;
        }

        // =============================================
        // 3. GRAPHIQUE : Répartition par département
        // =============================================
        $repartitionDept = Departement::withCount('employees')->get();
        $deptLabels = $repartitionDept->pluck('nom')->toArray();
        $deptData   = $repartitionDept->pluck('employees_count')->toArray();
        $deptColors = $repartitionDept->pluck('couleur')->toArray();

        // =============================================
        // 4. GRAPHIQUE : Évolution effectif
        // =============================================
        $evolutionData = [];
        $startCount = max(0, $totalEmployees - 5);
        for ($i = 0; $i < 7; $i++) {
            $evolutionData[] = $startCount + $i;
        }

        // =============================================
        // 5. TABLEAU : Congés en attente
        // =============================================
        $congesEnAttente = Conge::with('employee')
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // =============================================
        // 6. Envoi à la vue (SANS virgule finale)
        // =============================================
        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'pendingConges',
            'totalSalary',
            'recruitments',
            'notifications',
            'maleCount',
            'femaleCount',
            'congesData',
            'deptLabels',
            'deptData',
            'deptColors',
            'evolutionData',
            'congesEnAttente'
        ));
    }
}