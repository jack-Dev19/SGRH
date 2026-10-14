<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * EmployeController (Gestion)
 * ---------------------------
 * Partagé entre Administrateur et Responsable RH.
 */
class EmployeController extends Controller
{
    /**
     * Liste des employés avec recherche et filtres.
     */
    public function index(Request $request): View
    {
        // Requête de base avec relations
        $query = Employe::with(['departement', 'poste', 'user']);

        // ---------------------------------------------------------
        // Recherche : nom, prénom, matricule, email
        // ---------------------------------------------------------
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // ---------------------------------------------------------
        // Filtre : département
        // ---------------------------------------------------------
        if ($departement = $request->input('departement')) {
            $query->where('departement_id', $departement);
        }

        // ---------------------------------------------------------
        // Filtre : statut (actif / inactif)
        // ---------------------------------------------------------
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        // ---------------------------------------------------------
        // Pagination
        // ---------------------------------------------------------
        $employes = $query->orderBy('nom')->paginate(12)->withQueryString();

        // Liste des départements (pour les filtres de la vue)
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.employes.index', compact('employes', 'departements'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $departements = Departement::orderBy('nom')->get();
        $postes       = \App\Models\Poste::orderBy('nom')->get();

        return view('gestion.employes.create', compact('departements', 'postes'));
    }

    /**
     * Enregistrer un nouvel employé.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100'],
            'prenom'         => ['required', 'string', 'max:100'],
            'matricule'      => ['required', 'string', 'unique:employes,matricule'],
            'email'          => ['required', 'email', 'unique:employes,email'],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'sexe'           => ['nullable', 'in:Homme,Femme'],
            'date_naissance' => ['nullable', 'date'],
            'date_embauche'  => ['nullable', 'date'],
            'departement_id' => ['nullable', 'exists:departements,id'],
            'poste_id'       => ['nullable', 'exists:postes,id'],
            'adresse'        => ['nullable', 'string', 'max:255'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        Employe::create($validated);

        return redirect()
            ->route('gestion.employes.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /**
     * Fiche détaillée d'un employé.
     */
    public function show(string $id): View
    {
        $employe = Employe::with([
            'departement',
            'poste',
            'user',
            'contrats',
            'demandesConges.typeConge',
        ])->findOrFail($id);

        return view('gestion.employes.show', compact('employe'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $employe      = Employe::findOrFail($id);
        $departements = Departement::orderBy('nom')->get();
        $postes       = \App\Models\Poste::orderBy('nom')->get();

        return view('gestion.employes.edit', compact('employe', 'departements', 'postes'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $employe = Employe::findOrFail($id);

        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:100'],
            'prenom'         => ['required', 'string', 'max:100'],
            'matricule'      => ['required', 'string', 'unique:employes,matricule,' . $employe->id],
            'email'          => ['required', 'email', 'unique:employes,email,' . $employe->id],
            'telephone'      => ['nullable', 'string', 'max:20'],
            'sexe'           => ['nullable', 'in:Homme,Femme'],
            'date_naissance' => ['nullable', 'date'],
            'date_embauche'  => ['nullable', 'date'],
            'departement_id' => ['nullable', 'exists:departements,id'],
            'poste_id'       => ['nullable', 'exists:postes,id'],
            'adresse'        => ['nullable', 'string', 'max:255'],
            'statut'         => ['required', 'in:actif,inactif'],
        ]);

        $employe->update($validated);

        return redirect()
            ->route('gestion.employes.index')
            ->with('success', 'Employé modifié avec succès.');
    }

    /**
     * Supprimer un employé.
     */
    public function destroy(string $id)
    {
        $employe = Employe::findOrFail($id);
        $employe->delete();

        return redirect()
            ->route('gestion.employes.index')
            ->with('success', 'Employé supprimé avec succès.');
    }
}