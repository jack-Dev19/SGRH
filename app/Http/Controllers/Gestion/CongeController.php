<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\TypeConge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'    => ['required', 'exists:employes,id'],
            'type_conge_id' => ['required', 'exists:types_conges,id'],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['nullable', 'string', 'max:255'],
        ]);

        // Calcul du nombre de jours
        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);
        $validated['nombre_jours'] = $debut->diffInDays($fin) + 1;
        $validated['statut']       = 'en_attente';

        DemandeConge::create($validated);

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
    public function update(Request $request, string $id)
    {
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
        $validated['nombre_jours'] = $debut->diffInDays($fin) + 1;

        $conge->update($validated);

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
     * Approuver une demande.
     */
    public function approuver(string $id)
    {
        $conge = DemandeConge::findOrFail($id);

        $conge->update([
            'statut'                 => 'approuve',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => 'Demande approuvée.',
        ]);

        return redirect()
            ->back()
            ->with('success', "Congé de {$conge->employe?->nom_complet} approuvé.");
    }

    /**
     * Refuser une demande.
     */
    public function refuser(Request $request, string $id)
    {
        $conge = DemandeConge::findOrFail($id);

        $request->validate([
            'commentaire_validation' => ['nullable', 'string', 'max:255'],
        ]);

        $conge->update([
            'statut'                 => 'refuse',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => $request->input('commentaire_validation') ?? 'Demande refusée.',
        ]);

        return redirect()
            ->back()
            ->with('success', "Congé de {$conge->employe?->nom_complet} refusé.");
    }
}