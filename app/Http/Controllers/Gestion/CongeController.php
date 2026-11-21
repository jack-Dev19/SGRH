<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\TypeConge;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\CalculCongeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * CongeController (Gestion)
 * -------------------------
 * CRUD des demandes de congés + validation (approuver/refuser).
 * Partagé Admin + RH.
 */
class CongeController extends Controller
{
    /**
     * Liste des demandes avec filtres et onglets par statut.
     */
    public function index(Request $request): View
    {
        $query = DemandeConge::with(['employe', 'typeConge', 'validateur']);

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        // Filtre type
        if ($typeId = $request->input('type_conge_id')) {
            $query->where('type_conge_id', $typeId);
        }

        // Recherche employé
        if ($search = $request->input('search')) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $conges = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total'      => DemandeConge::count(),
            'en_attente' => DemandeConge::where('statut', 'en_attente')->count(),
            'approuve'   => DemandeConge::where('statut', 'approuve')->count(),
            'refuse'     => DemandeConge::where('statut', 'refuse')->count(),
        ];

        $typesConges = TypeConge::orderBy('nom')->get();

        return view('gestion.conges.index', compact('conges', 'stats', 'typesConges'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $employes    = Employe::orderBy('nom')->get();
        $typesConges = TypeConge::orderBy('nom')->get();

        return view('gestion.conges.create', compact('employes', 'typesConges'));
    }

    /**
     * Enregistrer une nouvelle demande.
     */
    public function store(
        Request $request,
        CalculCongeService $calculCongeService,
        NotificationService $notificationService
    ) {
        $validated = $request->validate([
            'employe_id'    => ['required', 'exists:employes,id'],
            'type_conge_id' => ['required', 'exists:types_conges,id'],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['nullable', 'string', 'max:255'],
        ]);
    
        // Calcul du nombre de jours ouvrés.
        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);

        $nombreJours = $calculCongeService->calculerJoursOuvres(
            $debut,
            $fin
        );

        if ($nombreJours === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'date_debut' => 'La période choisie ne contient aucun jour ouvré. Veuillez choisir d’autres dates.',
                ]);
        }

        $validated['nombre_jours'] = $nombreJours;
        $validated['statut'] = 'en_attente';
    
        // Récupérer l'employé, son compte et son département.
        $employe = Employe::with(['user', 'departement.responsable'])
            ->findOrFail($validated['employe_id']);
    
        // Créer le congé.
        $conge = DemandeConge::create($validated);
    
        // Identifier la personne qui a créé le congé.
        $roleValidateur = Auth::user()?->role?->nom;
    
        $acteur = match ($roleValidateur) {
            'Administrateur' => 'l’administrateur',
            'Responsable RH' => 'le responsable RH',
            default => 'le service RH',
        };
    
        $typeConge = TypeConge::find($validated['type_conge_id']);
        $typeCongeNom = $typeConge?->nom ?? 'Congé';
    
        $dates = "du {$debut->format('d/m/Y')} au {$fin->format('d/m/Y')}";
    
        // 1. Notifier l'employé concerné.
        if ($employe->user) {
            $notificationService->envoyer(
                $employe->user,
                'Nouveau congé créé',
                "Un congé de type {$typeCongeNom}, {$dates}, a été créé pour vous par {$acteur}.",
                'info',
                route('employe.conges.index'),
                'conges',
                $conge->id
            );
        }

        // 2. Notifier le Chef du département.
        $chef = $employe->departement?->responsable;

        if ($chef && $chef->hasRole('Chef de département')) {
            $notificationService->envoyer(
                $chef,
                'Nouveau congé dans votre équipe',
                "Un congé de type {$typeCongeNom} a été créé pour {$employe->nom_complet}, {$dates}, par {$acteur}.",
                'info',
                route('chef.conges.index'),
                'conges',
                $conge->id
            );
        }
    
        return redirect()
            ->route('gestion.conges.index')
            ->with('success', 'Demande de congé créée avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $conge = DemandeConge::with(['employe', 'typeConge', 'validateur'])->findOrFail($id);

        return view('gestion.conges.show', compact('conge'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $conge       = DemandeConge::findOrFail($id);
        $employes    = Employe::orderBy('nom')->get();
        $typesConges = TypeConge::orderBy('nom')->get();

        return view('gestion.conges.edit', compact('conge', 'employes', 'typesConges'));
    }

    /**
     * Enregistrer les modifications.
     */
    
    public function update(
        Request $request,
        string $id,
        CalculCongeService $calculCongeService,
        NotificationService $notificationService
    ) {
        $conge = DemandeConge::findOrFail($id);
    
        $validated = $request->validate([
            'employe_id'    => ['required', 'exists:employes,id'],
            'type_conge_id' => ['required', 'exists:types_conges,id'],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['nullable', 'string', 'max:255'],
        ]);
    
        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);

        $nombreJours = $calculCongeService->calculerJoursOuvres(
            $debut,
            $fin
        );

        if ($nombreJours === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'date_debut' => 'La période choisie ne contient aucun jour ouvré. Veuillez choisir d’autres dates.',
                ]);
        }

        $validated['nombre_jours'] = $nombreJours;
    
        // Récupérer l'ancien employé et son chef.
        $ancienEmploye = Employe::with(['user', 'departement.responsable'])
            ->findOrFail($conge->employe_id);
    
        $ancienChef = $ancienEmploye->departement?->responsable;
    
        if (!$ancienChef?->hasRole('Chef de département')) {
            $ancienChef = null;
        }
    
        // Récupérer le nouvel employé et son chef.
        $nouvelEmploye = Employe::with(['user', 'departement.responsable'])
            ->findOrFail($validated['employe_id']);
    
        $nouveauChef = $nouvelEmploye->departement?->responsable;
    
        if (!$nouveauChef?->hasRole('Chef de département')) {
            $nouveauChef = null;
        }
    
        // Identifier l'auteur de la modification.
        $roleValidateur = Auth::user()?->role?->nom;
    
        $acteur = match ($roleValidateur) {
            'Administrateur' => 'l’administrateur',
            'Responsable RH' => 'le responsable RH',
            default => 'le service RH',
        };
    
        $typeConge = TypeConge::find($validated['type_conge_id']);
        $typeCongeNom = $typeConge?->nom ?? 'Congé';
    
        $dates = "du {$debut->format('d/m/Y')} au {$fin->format('d/m/Y')}";
    
        DB::transaction(function () use (
            $conge,
            $validated,
            $ancienEmploye,
            $ancienChef,
            $nouvelEmploye,
            $nouveauChef,
            $typeCongeNom,
            $dates,
            $acteur,
            $notificationService
        ) {
            // Enregistrer les modifications du congé.
            $conge->update($validated);
    
            // 1. Supprimer l'ancienne notification si l'employé change.
            if (
                $ancienEmploye->user &&
                $ancienEmploye->user->id !== $nouvelEmploye->user?->id
            ) {
                $ancienEmploye->user->notifications()
                    ->where('demande_conge_id', $conge->id)
                    ->delete();
            }
    
            // 2. Mettre à jour ou créer la notification de l'employé.
            if ($nouvelEmploye->user) {
                $notificationService->envoyerOuActualiser(
                    $nouvelEmploye->user,
                    'Congé modifié',
                    "Votre congé de type {$typeCongeNom}, {$dates}, a été modifié par {$acteur}.",
                    'info',
                    route('employe.conges.index'),
                    'conges',
                    $conge->id
                );
            }
    
            // 3. Supprimer l'ancienne notification si le chef change.
            if (
                $ancienChef &&
                $ancienChef->id !== $nouveauChef?->id
            ) {
                $ancienChef->notifications()
                    ->where('demande_conge_id', $conge->id)
                    ->delete();
            }
    
            // 4. Mettre à jour ou créer la notification du nouveau chef.
            if ($nouveauChef) {
                $notificationService->envoyerOuActualiser(
                    $nouveauChef,
                    'Congé modifié dans votre équipe',
                    "Le congé de {$nouvelEmploye->nom_complet}, de type {$typeCongeNom}, {$dates}, a été modifié par {$acteur}.",
                    'info',
                    route('chef.conges.index'),
                    'conges',
                    $conge->id
                );
            }
        });
    
        return redirect()
            ->route('gestion.conges.index')
            ->with('success', 'Demande modifiée avec succès.');
    }

    /**
     * Supprimer une demande.
     */
    public function destroy(string $id)
    {
        $conge = DemandeConge::findOrFail($id);
        $conge->delete();

        return redirect()
            ->route('gestion.conges.index')
            ->with('success', 'Demande supprimée.');
    }

    // ==================================================================
    // ACTIONS MÉTIER
    // ==================================================================

    /**
     * Approuver une demande de congé.
     */
    public function approuver(
        string $id,
        NotificationService $notificationService
    )
    {
        $conge = DemandeConge::with('employe')->findOrFail($id);

        // Empêcher une nouvelle validation d'une demande déjà traitée.
        if ($conge->statut !== 'en_attente') {
            return redirect()
                ->back()
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        $conge->update([
            'statut'                 => 'approuve',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => 'Demande approuvée.',
        ]);

        // Récupérer le compte utilisateur associé à l'employé.
        $employe = $conge->employe;
        $user = $employe?->user_id
            ? User::find($employe->user_id)
            : null;

        $roleValidateur = Auth::user()?->role?->nom;

        $acteur = match ($roleValidateur) {
            'Administrateur' => 'l’administrateur',
            'Responsable RH' => 'le responsable RH',
            default => 'le service RH',
        };

        // Envoyer une notification à l'employé.
        if ($user) {
            $notificationService->envoyer(
                $user,
                'Demande de congé approuvée',
                "Votre demande de congé du {$conge->date_debut->format('d/m/Y')} au {$conge->date_fin->format('d/m/Y')} a été approuvée par {$acteur}.",
                'success',
                route('employe.conges.index'),
                'conges',
                $conge->id
            );
        }

        return redirect()
            ->back()
            ->with('success', "Congé de {$employe?->nom_complet} approuvé.");
    }

    /**
     * Refuser une demande de congé.
     */
    public function refuser(
        Request $request,
        string $id,
        NotificationService $notificationService
    )
    {
        $conge = DemandeConge::with('employe')->findOrFail($id);

        // Empêcher une nouvelle validation d'une demande déjà traitée.
        if ($conge->statut !== 'en_attente') {
            return redirect()
                ->back()
                ->with('error', 'Cette demande a déjà été traitée.');
        }

        $validated = $request->validate([
            'commentaire_validation' => ['nullable', 'string', 'max:255'],
        ]);

        $roleValidateur = Auth::user()?->role?->nom;

        $acteur = match ($roleValidateur) {
            'Administrateur' => 'l’administrateur',
            'Responsable RH' => 'le responsable RH',
            default => 'le service RH',
        };

        $commentaire = trim($validated['commentaire_validation'] ?? '');

        if ($commentaire === '') {
            $commentaire = "Demande refusée par {$acteur}.";
        }

        $conge->update([
            'statut'                 => 'refuse',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => $commentaire,
        ]);

        // Récupérer le compte utilisateur associé à l'employé.
        $employe = $conge->employe;
        $user = $employe?->user_id
            ? User::find($employe->user_id)
            : null;

        $roleValidateur = Auth::user()?->role?->nom;

        $acteur = match ($roleValidateur) {
            'Administrateur' => 'l’administrateur',
            'Responsable RH' => 'le responsable RH',
            default => 'le service RH',
        };

        // Envoyer une notification à l'employé.
        if ($user) {
            $notificationService->envoyer(
                $user,
                'Demande de congé refusée',
                "Votre demande de congé du {$conge->date_debut->format('d/m/Y')} au {$conge->date_fin->format('d/m/Y')} a été refusée par {$acteur}. Motif : {$commentaire}",
                'danger',
                route('employe.conges.index'),
                'conges',
                $conge->id
            );
        }

        return redirect()
            ->back()
            ->with('success', "Congé de {$employe?->nom_complet} refusé.");
    }
}