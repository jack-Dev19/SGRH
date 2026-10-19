<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Pointage;
use App\Services\PointageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PointageController extends Controller
{
    /**
     * ⭐ Délai minimum entre Check-in et Check-out (en minutes).
     */
    public const DELAI_MIN_CHECKOUT = 3;

    protected PointageService $pointageService;

    public function __construct(PointageService $pointageService)
    {
        $this->pointageService = $pointageService;
    }

    // =================================================================
    // SCANNER
    // =================================================================

    public function scanner(): View
    {
        $today = now()->toDateString();

        $pointagesJour = Pointage::with('employe')
            ->whereDate('date_heure', $today)
            ->orderByDesc('date_heure')
            ->take(15)
            ->get();

        $stats = [
            'arrivees'  => Pointage::whereDate('date_heure', $today)->where('type', 'entree')->count(),
            'departs'   => Pointage::whereDate('date_heure', $today)->where('type', 'sortie')->count(),
            'anomalies' => Pointage::whereDate('date_heure', $today)->where('est_anomalie', true)->count(),
        ];

        return view('gestion.pointage.scanner', compact('pointagesJour', 'stats'));
    }

    // =================================================================
    // ⭐ ENREGISTRER — avec délai minimum de 3 minutes
    // =================================================================

    public function enregistrer(Request $request)
    {
        $request->validate(['employee_code' => ['required', 'string']]);
        $code = trim($request->input('employee_code'));

        Log::info('Pointage - Code reçu', ['code' => $code]);

        // ⭐ 1. Chercher l'employé par matricule
        $employe = Employe::where('matricule', $code)->first();

        if (!$employe) {
            return response()->json([
                'success' => false,
                'type'    => 'error',
                'message' => "Employé introuvable : {$code}",
            ], 404);
        }

        if ($employe->statut !== 'actif') {
            return response()->json([
                'success' => false,
                'type'    => 'error',
                'message' => "L'employé {$employe->nom_complet} est inactif.",
            ], 403);
        }

        $today = now()->toDateString();

        // ⭐ 2. Chercher l'entrée et la sortie du jour
        $entree = Pointage::where('employe_id', $employe->id)
            ->whereDate('date_heure', $today)
            ->where('type', 'entree')
            ->first();

        $sortie = Pointage::where('employe_id', $employe->id)
            ->whereDate('date_heure', $today)
            ->where('type', 'sortie')
            ->first();

        // ⭐ 3. CAS 1 : Aucune entrée → CHECK-IN
        if (!$entree) {
            return $this->enregistrerCheckIn($employe);
        }

        // ⭐ 4. CAS 2 : Déjà entrée ET sortie → REFUS
        if ($entree && $sortie) {
            return response()->json([
                'success' => false,
                'type'    => 'already_completed',
                'message' => "{$employe->prenom} {$employe->nom} a déjà pointé son arrivée ET son départ aujourd'hui.",
            ], 409);
        }

        // ⭐ 5. CAS 3 : Entrée existe mais PAS de sortie → Vérifier le délai
        $maintenant   = now();
        $heureEntree  = Carbon::parse($entree->date_heure);
        $minutesEcoulees = $heureEntree->diffInMinutes($maintenant);
        $minutesRestantes = max(0, self::DELAI_MIN_CHECKOUT - $minutesEcoulees);

        Log::info('Pointage - Vérification délai', [
            'employe'         => $employe->matricule,
            'heure_entree'    => $heureEntree->format('H:i:s'),
            'minutes_ecoulees'=> $minutesEcoulees,
            'delai_requis'    => self::DELAI_MIN_CHECKOUT,
            'minutes_restantes' => $minutesRestantes,
        ]);

        // ⭐ 5a. Trop tôt → REFUS avec message
        if ($minutesEcoulees < self::DELAI_MIN_CHECKOUT) {
            return response()->json([
                'success'  => false,
                'type'     => 'too_early',
                'message'  => "⏱️ Veuillez patienter {$minutesRestantes} minute(s) avant de pouvoir pointer votre départ.",
                'minutes_ecoulees'  => $minutesEcoulees,
                'minutes_restantes' => $minutesRestantes,
                'heure_entree'      => $heureEntree->format('H:i:s'),
                'delai_requis'      => self::DELAI_MIN_CHECKOUT,
            ], 429);
        }

        // ⭐ 5b. Délai respecté → CHECK-OUT
        return $this->enregistrerCheckOut($employe, $entree);
    }

    // =================================================================
    // ⭐ HELPERS — Enregistrement
    // =================================================================

    /**
     * Enregistre un CHECK-IN.
     */
    private function enregistrerCheckIn(Employe $employe)
    {
        $pointage = Pointage::create([
            'employe_id' => $employe->id,
            'date_heure' => now(),
            'type'       => 'entree',
            'source'     => 'qr_code',
        ]);

        $this->pointageService->mettreAJourPresence($employe->id);

        Log::info('Pointage - CHECK-IN', [
            'employe' => $employe->matricule,
            'heure'   => now()->format('H:i:s'),
        ]);

        return response()->json([
            'success'  => true,
            'type'     => 'check_in',
            'message'  => "✅ Arrivée enregistrée pour {$employe->nom_complet}",
            'employee' => [
                'prenom' => $employe->prenom,
                'nom'    => $employe->nom,
                'code'   => $employe->matricule,
                'poste'  => $employe->poste?->nom,
            ],
            'heure'    => now()->format('H:i:s'),
            'message_extra' => "Vous pourrez pointer votre départ dans " . self::DELAI_MIN_CHECKOUT . " minutes.",
            'delai_requis'  => self::DELAI_MIN_CHECKOUT,
        ]);
    }

    /**
     * Enregistre un CHECK-OUT.
     */
    private function enregistrerCheckOut(Employe $employe, Pointage $entree)
    {
        $pointage = Pointage::create([
            'employe_id' => $employe->id,
            'date_heure' => now(),
            'type'       => 'sortie',
            'source'     => 'qr_code',
        ]);

        $this->pointageService->mettreAJourPresence($employe->id);

        // Calculer la durée de travail
        $heureEntree = Carbon::parse($entree->date_heure);
        $heureSortie = now();
        $dureeMinutes = $heureEntree->diffInMinutes($heureSortie);
        $dureeHeures  = round($dureeMinutes / 60, 2);

        Log::info('Pointage - CHECK-OUT', [
            'employe' => $employe->matricule,
            'heure'   => $heureSortie->format('H:i:s'),
            'duree'   => "{$dureeHeures}h",
        ]);

        return response()->json([
            'success'       => true,
            'type'          => 'check_out',
            'message'       => "✅ Départ enregistré pour {$employe->nom_complet}",
            'employee'      => [
                'prenom' => $employe->prenom,
                'nom'    => $employe->nom,
                'code'   => $employe->matricule,
                'poste'  => $employe->poste?->nom,
            ],
            'heure_arrivee' => $heureEntree->format('H:i:s'),
            'heure_depart'  => $heureSortie->format('H:i:s'),
            'duree_minutes' => $dureeMinutes,
            'duree_heures'  => $dureeHeures,
            'message_extra' => "Durée de travail : {$dureeHeures}h",
        ]);
    }

    // =================================================================
    // LISTE / HISTORIQUE / CORRECTION / ANOMALIES
    // =================================================================

    public function liste()
    {
        $today = now()->toDateString();

        $pointages = Pointage::with('employe')
            ->whereDate('date_heure', $today)
            ->orderByDesc('date_heure')
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'matricule' => $p->employe?->matricule,
                'nom'       => $p->employe?->nom_complet,
                'type'      => $p->type,
                'heure'     => $p->date_heure?->format('H:i:s'),
                'anomalie'  => $p->est_anomalie,
            ]);

        $stats = [
            'arrivees'  => Pointage::whereDate('date_heure', $today)->where('type', 'entree')->count(),
            'departs'   => Pointage::whereDate('date_heure', $today)->where('type', 'sortie')->count(),
            'anomalies' => Pointage::whereDate('date_heure', $today)->where('est_anomalie', true)->count(),
        ];

        return response()->json([
            'pointages'  => $pointages,
            'stats'      => $stats,
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

    public function updateCorrection(Request $request, string $id)
    {
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