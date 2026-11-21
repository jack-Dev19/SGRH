<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Pointage;
use App\Services\PointageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PointageController extends Controller
{
    public function __construct(
        protected PointageService $pointageService
    ) {}

    public const DELAI_MIN_CHECKOUT = 3;

    public function scanner(): View
    {
        $today = now()->toDateString();

        $pointagesJour = Pointage::with('employe')
            ->whereDate('date_heure', $today)
            ->latest('date_heure')
            ->take(15)
            ->get();

        $stats = [
            'arrivees' => Pointage::whereDate('date_heure', $today)
                ->where('type', 'entree')->count(),

            'departs' => Pointage::whereDate('date_heure', $today)
                ->where('type', 'sortie')->count(),

            'anomalies' => Pointage::whereDate('date_heure', $today)
                ->where('est_anomalie', true)->count(),
        ];

        return view('gestion.pointage.scanner', compact(
            'pointagesJour',
            'stats'
        ));
    }

    public function enregistrer(Request $request)
    {
        $request->validate([
            'employee_code' => ['required', 'string'],
        ]);

        $employe = Employe::where(
            'matricule',
            trim($request->employee_code)
        )->first();

        if (!$employe) {
            return response()->json([
                'success' => false,
                'message' => 'Employé introuvable.',
            ], 404);
        }

        if ($employe->statut !== 'actif') {
            return response()->json([
                'success' => false,
                'message' => 'Employé inactif.',
            ], 403);
        }

        $today = now()->toDateString();

        $entree = Pointage::where('employe_id', $employe->id)
            ->whereDate('date_heure', $today)
            ->where('type', 'entree')
            ->first();

        $sortie = Pointage::where('employe_id', $employe->id)
            ->whereDate('date_heure', $today)
            ->where('type', 'sortie')
            ->first();

        if (!$entree) {
            return $this->enregistrerCheckIn($employe);
        }

        if ($sortie) {
            return response()->json([
                'success' => false,
                'type' => 'already_completed',
                'message' => 'Arrivée et départ déjà enregistrés.',
            ], 409);
        }

        $minutes = $entree->date_heure->diffInMinutes(now());

        if ($minutes < self::DELAI_MIN_CHECKOUT) {
            return response()->json([
                'success' => false,
                'type' => 'too_early',
                'message' => 'Veuillez patienter avant de pointer votre départ.',
                'minutes_restantes' => self::DELAI_MIN_CHECKOUT - $minutes,
            ], 429);
        }

        return $this->enregistrerCheckOut($employe, $entree);
    }

    private function enregistrerCheckIn(Employe $employe)
    {
        $maintenant = now();

        $pointage = Pointage::create([
            'employe_id' => $employe->id,
            'date_heure' => $maintenant,
            'type' => 'entree',
            'source' => 'qr_code',
            'type_scan' => 'qr_code',
        ]);

        if ($maintenant->format('H:i:s') > PointageService::HEURE_LIMITE_ARRIVEE) {
            $this->pointageService->marquerAnomalie(
                $pointage,
                'Retard de ' . $maintenant->diffInMinutes(
                    $maintenant->copy()->setTime(8, 15)
                ) . ' minutes'
            );
        }

        $this->pointageService->mettreAJourPresence($employe->id);

        return response()->json([
            'success' => true,
            'type' => 'check_in',
            'message' => 'Arrivée enregistrée pour ' . $employe->nom_complet,
            'heure' => $maintenant->format('H:i:s'),
        ]);
    }

    private function enregistrerCheckOut(
        Employe $employe,
        Pointage $entree
    ) {
        $maintenant = now();

        $pointage = Pointage::create([
            'employe_id' => $employe->id,
            'date_heure' => $maintenant,
            'type' => 'sortie',
            'source' => 'qr_code',
            'type_scan' => 'qr_code',
        ]);

        if ($maintenant->format('H:i:s') < PointageService::HEURE_MIN_DEPART) {
            $this->pointageService->marquerAnomalie(
                $pointage,
                'Départ anticipé : avant 16:45'
            );
        }

        $this->pointageService->mettreAJourPresence($employe->id);

        $duree = $entree->date_heure->diffInMinutes($maintenant);

        return response()->json([
            'success' => true,
            'type' => 'check_out',
            'message' => 'Départ enregistré pour ' . $employe->nom_complet,
            'heure' => $maintenant->format('H:i:s'),
            'duree_heures' => round($duree / 60, 2),
        ]);
    }

    public function anomalies(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());

        $anomalies = $this->pointageService->detecterAnomalies($date);

        return view('gestion.pointage.anomalies', compact(
            'anomalies',
            'date'
        ));
    }

    public function historique(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());

        $query = Pointage::with('employe')
            ->whereDate('date_heure', $date);

        if ($request->filled('employe_id')) {
            $query->where('employe_id', $request->employe_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->boolean('anomalies')) {
            $query->where('est_anomalie', true);
        }

        $pointages = $query->latest('date_heure')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Pointage::whereDate('date_heure', $date)->count(),
            'arrivees' => Pointage::whereDate('date_heure', $date)
                ->where('type', 'entree')->count(),
            'departs' => Pointage::whereDate('date_heure', $date)
                ->where('type', 'sortie')->count(),
            'anomalies' => Pointage::whereDate('date_heure', $date)
                ->where('est_anomalie', true)->count(),
        ];

        $employes = Employe::orderBy('nom')->get();

        return view('gestion.pointage.historique', compact(
            'pointages',
            'stats',
            'employes',
            'date'
        ));
    }

    public function corriger(string $id): View
    {
        $pointage = Pointage::with('employe')->findOrFail($id);

        return view('gestion.pointage.corriger', compact('pointage'));
    }

    public function updateCorrection(Request $request, string $id)
    {
        $validated = $request->validate([
            'date_heure' => ['required', 'date'],
            'type' => ['required', 'in:entree,sortie'],
            'raison' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        $pointage = Pointage::findOrFail($id);

        $this->pointageService->corriger(
            $pointage,
            $validated,
            $validated['raison']
        );

        return redirect()
            ->route('gestion.pointage.anomalies')
            ->with('success', 'Pointage corrigé avec succès.');
    }

    public function supprimerPointage(string $id)
    {
        $pointage = Pointage::findOrFail($id);
        $employeId = $pointage->employe_id;

        $pointage->delete();

        $this->pointageService->mettreAJourPresence($employeId);

        return back()->with('success', 'Pointage supprimé.');
    }

    public function supprimerParEmploye(
        Request $request,
        string $employeId
    ) {
        $employe = Employe::findOrFail($employeId);

        $query = Pointage::where('employe_id', $employeId);

        if ($request->filled('date_debut')) {
            $query->whereDate('date_heure', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_heure', '<=', $request->date_fin);
        }

        $count = $query->count();
        $query->delete();

        return back()->with(
            'success',
            "{$count} pointage(s) supprimé(s) pour {$employe->nom_complet}."
        );
    }

    public function liste()
    {
        $today = now()->toDateString();

        $pointages = Pointage::with('employe')
            ->whereDate('date_heure', $today)
            ->latest('date_heure')
            ->get();

        return response()->json([
            'pointages' => $pointages,
            'updated_at' => now()->format('H:i:s'),
        ]);
    }
}
