<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PosteController (Gestion)
 * -------------------------
 * CRUD des postes (partagé Admin + RH).
 */
class PosteController extends Controller
{
    /**
     * Liste des postes avec filtres.
     */
    public function index(Request $request): View
    {
        $query = Poste::with('departement')->withCount('employes');

        // Recherche par nom
        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        // Filtre par département
        if ($departementId = $request->input('departement_id')) {
            $query->where('departement_id', $departementId);
        }

        // Filtre par statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $postes       = $query->orderBy('nom')->paginate(12)->withQueryString();
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.index', compact('postes', 'departements'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.create', compact('departements'));
    }

    /**
     * Enregistrer un nouveau poste.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100'],
            'departement_id' => ['required', 'exists:departements,id'],
            'description'    => ['nullable', 'string', 'max:255'],
            'niveau'         => ['nullable', 'string', 'max:50'],
            'salaire_min'    => ['nullable', 'numeric', 'min:0'],
            'salaire_max'    => ['nullable', 'numeric', 'min:0', 'gte:salaire_min'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        Poste::create($validated);

        return redirect()
            ->route('gestion.postes.index')
            ->with('success', 'Poste créé avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $poste = Poste::with([
            'departement',
            'employes',
        ])->withCount('employes')->findOrFail($id);

        return view('gestion.postes.show', compact('poste'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $poste        = Poste::findOrFail($id);
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.edit', compact('poste', 'departements'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $poste = Poste::findOrFail($id);

        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100'],
            'departement_id' => ['nullable', 'exists:departements,id'],
            'description'    => ['nullable', 'string', 'max:255'],
            'niveau'         => ['nullable', 'string', 'max:50'],
            'salaire_min'    => ['nullable', 'numeric', 'min:0'],
            'salaire_max'    => ['nullable', 'numeric', 'min:0', 'gte:salaire_min'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        $poste->update($validated);

        return redirect()
            ->route('gestion.postes.index')
            ->with('success', 'Poste modifié avec succès.');
    }

    /**
     * Supprimer un poste.
     * ⚠️ Refusé si des employés y sont rattachés.
     */
    public function destroy(string $id)
    {
        $poste = Poste::withCount('employes')->findOrFail($id);

        if ($poste->employes_count > 0) {
            return redirect()
                ->route('gestion.postes.index')
                ->with('error', "Impossible de supprimer : {$poste->employes_count} employé(s) occupent ce poste.");
        }

        $poste->delete();

        return redirect()
            ->route('gestion.postes.index')
            ->with('success', 'Poste supprimé.');
    }
}