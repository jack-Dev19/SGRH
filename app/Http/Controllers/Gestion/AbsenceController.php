<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Employe;
use App\Models\HistoriqueAction;
use App\Services\AbsenceService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * AbsenceController
 * -----------------
 * CRUD des absences + validation + détection automatique.
 * Partagé entre Administrateur et Responsable RH.
 *
 * Statuts possibles :
 *   - en_attente    : en attente de validation
 *   - justifie      : absence justifiée (validée)
 *   - non_justifie  : absence non justifiée (refusée)
 *   - autorise      : autorisation exceptionnelle
 *   - annule        : annulée
 *
 * Origines possibles :
 *   - auto     : détectée automatiquement par le scheduler
 *   - manuelle : créée manuellement par RH/Admin
 */
class AbsenceController extends Controller
{
    protected AbsenceService $service;

    public function __construct(AbsenceService $service)
    {
        $this->service = $service;
    }

    // =================================================================
    // 1. LISTE — Index avec filtres
    // =================================================================

    public function index(Request $request): View
    {
        $query = Absence::with('employe');

        // --- Filtre statut ---
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        // --- Filtre origine ---
        if ($origine = $request->input('origine')) {
            $query->where('origine', $origine);
        }

        // --- Recherche employé ---
        if ($search = $request->input('search')) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // --- Filtres par dates ---
        if ($dateDebut = $request->input('date_debut')) {
            $query->whereDate('date_debut', '>=', $dateDebut);
        }
        if ($dateFin = $request->input('date_fin')) {
            $query->whereDate('date_fin', '<=', $dateFin);
        }

        $absences = $query->orderByDesc('date_debut')->paginate(12)->withQueryString();

        // --- Stats pour les onglets ---
        $stats = [
            'total'        => Absence::count(),
            'en_attente'   => Absence::where('statut', 'en_attente')->count(),
            'justifie'     => Absence::where('statut', 'justifie')->count(),
            'non_justifie' => Absence::where('statut', 'non_justifie')->count(),
            'autorise'     => Absence::where('statut', 'autorise')->count(),
        ];

        return view('gestion.absences.index', compact('absences', 'stats'));
    }

    // =================================================================
    // 2. CRÉATION
    // =================================================================

    public function create(): View
    {
        $employes = Employe::where('statut', 'actif')->orderBy('nom')->get();

        return view('gestion.absences.create', compact('employes'));
    }

    public function store(
        Request $request,
        NotificationService $notificationService
    ) {
        $validated = $request->validate([
            'employe_id'    => ['required', 'exists:employes,id'],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:500'],
            'justificatif'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'statut'        => ['required', 'in:en_attente,justifie,non_justifie,autorise'],
        ]);

        // Calcul du nombre de jours
        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);
        $validated['nombre_jours'] = $debut->diffInDays($fin) + 1;

        // Upload du justificatif (si fourni)
        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            $path = $file->store('justificatifs/absences', 'public');
            $validated['justificatif']      = $path;
            $validated['justificatif_path'] = $path;
        }

        $validated['origine'] = 'manuelle';

        // Si le RH valide directement (statut != en_attente)
        if ($validated['statut'] !== 'en_attente') {
            $validated['valide_par']      = Auth::id();
            $validated['date_validation'] = now();
        }

        $absence = Absence::create($validated);

        // Notifier l'employé de l'enregistrement de son absence.
        $absence->load('employe.user');
        
        if ($absence->employe?->user) {
            $statutMessage = match ($absence->statut) {
                'en_attente' => 'enregistrée et en attente de validation',
                'justifie' => 'enregistrée et justifiée',
                'non_justifie' => 'enregistrée et non justifiée',
                'autorise' => 'enregistrée et autorisée',
                default => 'enregistrée',
            };
        
            $notificationService->envoyer(
                $absence->employe->user,
                'Absence enregistrée',
                "Une absence du {$absence->date_debut->format('d/m/Y')} au {$absence->date_fin->format('d/m/Y')} a été {$statutMessage}.",
                'info',
                null,
                'absences'
            );
        }

        // Log
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'creation',
            'module'      => 'absences',
            'description' => "Absence créée pour {$absence->employe?->nom_complet} ({$absence->date_debut->format('d/m/Y')})",
            'nouvelle_valeur' => json_encode($absence->only(['id', 'statut'])),
        ]);

        return redirect()
            ->route('gestion.absences.index')
            ->with('success', 'Absence créée avec succès.');
    }

    // =================================================================
    // 3. AFFICHAGE
    // =================================================================

    public function show(string $id): View
    {
        $absence = Absence::with(['employe', 'validateur'])->findOrFail($id);

        return view('gestion.absences.show', compact('absence'));
    }

    // =================================================================
    // 4. MODIFICATION
    // =================================================================

    public function edit(string $id): View
    {
        $absence  = Absence::findOrFail($id);
        $employes = Employe::where('statut', 'actif')->orderBy('nom')->get();

        return view('gestion.absences.edit', compact('absence', 'employes'));
    }

    public function update(
        Request $request,
        string $id,
        NotificationService $notificationService
    ) {
        $absence = Absence::findOrFail($id);
        $ancienStatut = $absence->statut;

        $validated = $request->validate([
            'employe_id'    => ['required', 'exists:employes,id'],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:500'],
            'justificatif'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'statut'        => ['required', 'in:en_attente,justifie,non_justifie,autorise,annule'],
        ]);

        // Recalcul du nombre de jours
        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);
        $validated['nombre_jours'] = $debut->diffInDays($fin) + 1;

        // Nouveau justificatif
        if ($request->hasFile('justificatif')) {
            // Supprimer l'ancien
            if ($absence->justificatif && Storage::disk('public')->exists($absence->justificatif)) {
                Storage::disk('public')->delete($absence->justificatif);
            }

            $path = $request->file('justificatif')->store('justificatifs/absences', 'public');
            $validated['justificatif']      = $path;
            $validated['justificatif_path'] = $path;
        }

        // Si le statut passe à justifié/refusé → enregistrer le validateur
        if ($validated['statut'] !== 'en_attente' && $absence->statut === 'en_attente') {
            $validated['valide_par']      = Auth::id();
            $validated['date_validation'] = now();
        }

        $absence->update($validated);

        // Notifier l'employé après la modification de son absence.
        $absence->load('employe.user');

        if ($absence->employe?->user) {
            $statutModifie = $ancienStatut !== $absence->statut;

            $libellesStatuts = [
                'en_attente'   => 'en attente de validation',
                'justifie'     => 'justifiée',
                'non_justifie' => 'non justifiée',
                'autorise'     => 'autorisée',
                'annule'       => 'annulée',
            ];

            $statutMessage = $libellesStatuts[$absence->statut] ?? $absence->statut;

            $titre = $statutModifie
                ? 'Statut de votre absence modifié'
                : 'Absence modifiée';

            $message = $statutModifie
                ? "Le statut de votre absence du {$absence->date_debut->format('d/m/Y')} au {$absence->date_fin->format('d/m/Y')} a été modifié : elle est maintenant {$statutMessage}."
                : "Votre absence du {$absence->date_debut->format('d/m/Y')} au {$absence->date_fin->format('d/m/Y')} a été modifiée.";

            $notificationService->envoyer(
                $absence->employe->user,
                $titre,
                $message,
                $statutModifie ? 'info' : 'info',
                null,
                'absences'
            );
        }

        // Log
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'modification',
            'module'      => 'absences',
            'description' => "Absence modifiée pour {$absence->employe?->nom_complet}",
        ]);

        return redirect()
            ->route('gestion.absences.index')
            ->with('success', 'Absence modifiée avec succès.');
    }

    // =================================================================
    // 5. SUPPRESSION
    // =================================================================

    public function destroy(string $id)
    {
        $absence = Absence::findOrFail($id);
        $ancienStatut = $absence->statut;

        // Supprimer le justificatif
        if ($absence->justificatif && Storage::disk('public')->exists($absence->justificatif)) {
            Storage::disk('public')->delete($absence->justificatif);
        }

        $nom = $absence->employe?->nom_complet;
        $absence->delete();

        // Log
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'suppression',
            'module'      => 'absences',
            'description' => "Absence supprimée pour {$nom}",
        ]);

        return redirect()
            ->route('gestion.absences.index')
            ->with('success', 'Absence supprimée.');
    }

    // =================================================================
    // 6. VALIDATION — Approuver / Refuser
    // =================================================================

    /**
     * Approuver une absence → statut "justifie".
     */
    public function approuver(
        Request $request,
        string $id,
        NotificationService $notificationService
    )
    {
        $absence = Absence::findOrFail($id);

        if ($absence->statut !== 'en_attente') {
            return redirect()
                ->back()
                ->with('error', 'Cette absence a déjà été traitée.');
        }

        $request->validate([
            'commentaire_validation' => ['nullable', 'string', 'max:255'],
        ]);

        $absence->update([
            'statut'                 => 'justifie',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => $request->input('commentaire_validation') ?? 'Absence justifiée et approuvée.',
        ]);

        // Notification de l'employé
        if ($absence->employe?->user) {
            $notificationService->envoyer(
                $absence->employe->user,
                'Absence approuvée',
                "Votre absence du {$absence->date_debut->format('d/m/Y')} au {$absence->date_fin->format('d/m/Y')} a été approuvée.",
                'success',
                null,
                'absences'
            );
        }

        // Log
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'validation',
            'module'      => 'absences',
            'description' => "Absence approuvée pour {$absence->employe?->nom_complet}",
        ]);

        return redirect()
            ->back()
            ->with('success', "Absence de {$absence->employe?->nom_complet} approuvée.");
    }

    /**
     * Refuser une absence → statut "non_justifie".
     */
        public function refuser(
        Request $request,
        string $id,
        NotificationService $notificationService
    ) {
        $absence = Absence::findOrFail($id);

        if ($absence->statut !== 'en_attente') {
            return redirect()
                ->back()
                ->with('error', 'Cette absence a déjà été traitée.');
        }

        $request->validate([
            'commentaire_validation' => ['nullable', 'string', 'max:255'],
        ]);

        $absence->update([
            'statut'                 => 'non_justifie',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => $request->input('commentaire_validation') ?? 'Absence non justifiée.',
        ]);

        // Notification de l'employé
        if ($absence->employe?->user) {
            $notificationService->envoyer(
                $absence->employe->user,
                'Absence refusée',
                "Votre absence du {$absence->date_debut->format('d/m/Y')} au {$absence->date_fin->format('d/m/Y')} a été refusée. Motif : {$absence->commentaire_validation}",
                'danger',
                null,
                'absences'
            );
        }

        // Log
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'validation',
            'module'      => 'absences',
            'description' => "Absence refusée pour {$absence->employe?->nom_complet}",
        ]);

        return redirect()
            ->back()
            ->with('success', "Absence de {$absence->employe?->nom_complet} refusée.");
    }

    // =================================================================
    // 7. DÉTECTION AUTOMATIQUE (bouton RH)
    // =================================================================

    /**
     * Lancer la détection automatique des absences pour aujourd'hui.
     * Route : POST /gestion/absences/detecter
     */
    public function detecter(Request $request)
    {
        // Date optionnelle (sinon aujourd'hui)
        $date = $request->input('date', now()->toDateString());

        try {
            $resultat = $this->service->detecterAbsences($date);

            // Log
            HistoriqueAction::create([
                'user_id'     => Auth::id(),
                'action'      => 'creation',
                'module'      => 'absences',
                'description' => "Détection auto des absences ({$date}) : {$resultat['crees']} créée(s), {$resultat['ignores']} ignorée(s)",
            ]);

            return redirect()
                ->route('gestion.absences.index')
                ->with('success', "Détection terminée : {$resultat['crees']} absence(s) créée(s), {$resultat['ignores']} ignorée(s).");

        } catch (\Exception $e) {
            \Log::error('Erreur détection absences : ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', "Erreur lors de la détection : {$e->getMessage()}");
        }
    }
}