<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * DepartementController (Gestion)
 * -------------------------------
 * Partagé entre Administrateur et Responsable RH.
 */
class DepartementController extends Controller
{
    /**
     * Liste des départements avec compteurs.
     */
    public function index(Request $request): View
    {
        $query = Departement::with(['responsable'])
            ->withCount(['employes', 'postes']);

        // Recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departements = $query->orderBy('nom')->paginate(12)->withQueryString();

        return view('gestion.departements.index', compact('departements'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        // Liste des employés pour choisir un responsable
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.departements.create', compact('employes'));
    }

    /**
     * Enregistrer un nouveau département.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100', 'unique:departements,nom'],
            'description'    => ['nullable', 'string', 'max:255'],
            'responsable_id' => ['nullable', 'exists:employes,id'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        Departement::create($validated);

        return redirect()
            ->route('gestion.departements.index')
            ->with('success', 'Département créé avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $departement = Departement::with([
            'responsable',
            'employes.poste',
            'postes',
        ])->withCount(['employes', 'postes'])->findOrFail($id);

        return view('gestion.departements.show', compact('departement'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $departement = Departement::findOrFail($id);
        $employes    = Employe::orderBy('nom')->get();

        return view('gestion.departements.edit', compact('departement', 'employes'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $departement = Departement::findOrFail($id);

        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100', 'unique:departements,nom,' . $departement->id],
            'description'    => ['nullable', 'string', 'max:255'],
            'responsable_id' => ['nullable', 'exists:employes,id'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        $departement->update($validated);

        return redirect()
            ->route('gestion.departements.index')
            ->with('success', 'Département modifié avec succès.');
    }

    /**
     * Supprimer un département uniquement s'il n'est utilisé
     * ni par des employés ni par des postes.
     */
    public function destroy(string $id)
    {
        // Récupérer le département et compter ses dépendances.
        $departement = Departement::withCount([
            'employes',
            'postes',
        ])->findOrFail($id);
    
        // Empêcher la suppression si le département est utilisé.
        if ($departement->employes_count > 0 ||
            $departement->postes_count > 0) {
    
            return redirect()
                ->route('gestion.departements.index')
                ->with(
                    'error',
                    'Impossible de supprimer ce département : '
                    . $departement->employes_count
                    . ' employé(s) et '
                    . $departement->postes_count
                    . ' poste(s) y sont rattachés.'
                );
        }
    
        // Supprimer uniquement si aucune dépendance n'existe.
        $departement->delete();
    
        return redirect()
            ->route('gestion.departements.index')
            ->with('success', 'Département supprimé avec succès.');
    }
}