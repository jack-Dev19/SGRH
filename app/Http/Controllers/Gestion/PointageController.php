<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Pointage;
use App\Services\PointageService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PointageController extends Controller
{
    public function __construct(
        protected PointageService $pointageService,
        protected NotificationService $notificationService
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
            $heureLimite = $maintenant->copy()->setTime(8, 15);

            $minutesRetard = max(
                0,
                (int) $heureLimite->diffInMinutes($maintenant, false)
            );

            $retard = "Retard de {$minutesRetard} minutes";

            $this->pointageService->marquerAnomalie($pointage, $retard);

            $employe->loadMissing('user');

            if ($employe->user) {
                $this->notificationService->envoyer(
                    $employe->user,
                    'Retard à l’arrivée',
                    "Votre pointage d’arrivée du {$maintenant->format('d/m/Y à H:i')} a été enregistré comme retard. {$retard}.",
                    'info',
                    null,
                    'presence'
                );
            }
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
            $heureLimite = $maintenant->copy()->setTime(16, 45);
        
            $minutesAnticipees = max(
                0,
                (int) $maintenant->diffInMinutes($heureLimite, false)
            );
        
            $anomalie = "Départ anticipé de {$minutesAnticipees} minutes";
        
            $this->pointageService->marquerAnomalie($pointage, $anomalie);
        
            $employe->loadMissing('user');
        
            if ($employe->user) {
                $this->notificationService->envoyer(
                    $employe->user,
                    'Départ anticipé',
                    "Votre pointage de sortie du {$maintenant->format('d/m/Y à H:i')} a été enregistré comme départ anticipé. {$anomalie}.",
                    'info',
                    null,
                    'presence'
                );
            }
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

    public function historique(Request $request): View
    {
        $query = Pointage::with('employe');
        $date  = $request->input('date', now()->toDateString());
        $query->whereDate('date_heure', $date);

        if ($employeId = $request->input('employe_id')) {
            $query->where('employe_id', $employeId);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($request->boolean('anomalies')) {
            $query->where('est_anomalie', true);
        }

        $pointages = $query->orderByDesc('date_heure')->paginate(20)->withQueryString();

        $stats = [
            'total'     => Pointage::whereDate('date_heure', $date)->count(),
            'arrivees'  => Pointage::whereDate('date_heure', $date)->where('type', 'entree')->count(),
            'departs'   => Pointage::whereDate('date_heure', $date)->where('type', 'sortie')->count(),
            'anomalies' => Pointage::whereDate('date_heure', $date)->where('est_anomalie', true)->count(),
        ];

        $employes = Employe::orderBy('nom')->get();

        return view('gestion.pointage.historique', compact('pointages', 'stats', 'employes', 'date'));
    }

    public function corriger(string $id): View
    {
        $pointage = Pointage::with('employe')->findOrFail($id);
        return view('gestion.pointage.corriger', compact('pointage'));
    }

    public function updateCorrection(
        Request $request,
        string $id,
        NotificationService $notificationService
    ) {
        $pointage = Pointage::findOrFail($id);

        $validated = $request->validate([
            'date_heure' => ['required', 'date'],
            'type'       => ['required', 'in:entree,sortie'],
            'raison'     => ['required', 'string', 'min:5', 'max:255'],
        ]);

        $this->pointageService->corriger($pointage, [
            'date_heure' => $validated['date_heure'],
            'type'       => $validated['type'],
        ], $validated['raison']);

        $pointage->refresh()->load('employe.user');

        if ($pointage->employe?->user) {
            $typePointage = $pointage->type === 'entree' ? 'entrée' : 'sortie';
            $dateHeure = Carbon::parse($pointage->date_heure)->format('d/m/Y à H:i');
        
            $notificationService->envoyer(
                $pointage->employe->user,
                'Pointage corrigé',
                "Votre pointage de {$typePointage} du {$dateHeure} a été corrigé par le service RH. Motif : {$validated['raison']}",
                'info',
                null,
                'presence'
            );
        }

        return redirect()
            ->route('gestion.pointage.historique')
            ->with('success', 'Pointage corrigé.');
    }

    public function anomalies(Request $request): View
    {
        $date      = $request->input('date', now()->toDateString());
        $anomalies = $this->pointageService->detecterAnomalies($date);

        return view('gestion.pointage.anomalies', compact('anomalies', 'date'));
    }

    // =================================================================
    // SUPPRESSION
    // =================================================================

    public function supprimerPointage(string $id)
    {
        $pointage = Pointage::with('employe')->findOrFail($id);

        $nomEmploye = $pointage->employe?->nom_complet ?? 'Inconnu';
        $type       = $pointage->type === 'entree' ? 'arrivée' : 'départ';
        $heure      = $pointage->date_heure?->format('d/m/Y H:i') ?? '';
        $employeId  = $pointage->employe_id;

        $pointage->delete();

        if ($employeId) {
            try {
                $this->pointageService->mettreAJourPresence($employeId);
            } catch (\Exception $e) {
                Log::warning('Erreur recalcul presences : ' . $e->getMessage());
            }
        }

        return redirect()
            ->back()
            ->with('success', "Pointage {$type} du {$heure} pour {$nomEmploye} supprimé avec succès.");
    }

    public function supprimerParEmploye(Request $request, string $employeId)
    {
        $employe = Employe::findOrFail($employeId);

        $query = Pointage::where('employe_id', $employe->id);

        if ($dateDebut = $request->input('date_debut')) {
            $query->whereDate('date_heure', '>=', $dateDebut);
        }
        if ($dateFin = $request->input('date_fin')) {
            $query->whereDate('date_heure', '<=', $dateFin);
        }

        $count = $query->count();

        if ($count === 0) {
            return redirect()
                ->back()
                ->with('error', 'Aucun pointage à supprimer pour cet employé.');
        }

        $query->delete();

        try {
            $this->pointageService->mettreAJourPresence($employe->id);
        } catch (\Exception $e) {
            // Ignorer
        }

        return redirect()
            ->back()
            ->with('success', "{$count} pointage(s) supprimé(s) pour {$employe->nom_complet}.");
    }
}
