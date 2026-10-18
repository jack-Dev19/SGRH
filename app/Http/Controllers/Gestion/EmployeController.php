<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

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
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],

            'matricule' => [
                'required',
                'string',
                'max:30',
                'unique:employes,matricule',
            ],

            'email' => [
                'required',
                'email',
                'unique:employes,email',
            ],

            'telephone' => ['nullable', 'string', 'max:20'],
            'sexe' => ['nullable', 'in:Homme,Femme'],
            'date_naissance' => ['nullable', 'date'],
            'date_embauche' => ['required', 'date'],

            'departement_id' => [
                'required',
                'uuid',
                'exists:departements,id',
            ],

            'poste_id' => [
                'required',
                'uuid',
                Rule::exists('postes', 'id')
                    ->where(fn ($query) => $query->where(
                        'departement_id',
                        $request->input('departement_id')
                    )),
            ],

            'adresse' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', 'in:actif,inactif'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule existe déjà.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'date_embauche.required' => 'La date d’embauche est obligatoire.',
            'departement_id.required' => 'Veuillez sélectionner un département.',
            'departement_id.exists' => 'Le département sélectionné est introuvable.',
            'poste_id.required' => 'Veuillez sélectionner un poste.',
            'poste_id.exists' => 'Ce poste n’appartient pas au département sélectionné.',
            'statut.required' => 'Veuillez sélectionner un statut.',
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
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],

            'matricule' => [
                'required',
                'string',
                'max:30',
                Rule::unique('employes', 'matricule')->ignore($employe->id),
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('employes', 'email')->ignore($employe->id),
            ],

            'telephone' => ['nullable', 'string', 'max:20'],
            'sexe' => ['nullable', 'in:Homme,Femme'],
            'date_naissance' => ['nullable', 'date'],
            'date_embauche' => ['required', 'date'],

            'departement_id' => [
                'required',
                'uuid',
                'exists:departements,id',
            ],

            'poste_id' => [
                'required',
                'uuid',
                Rule::exists('postes', 'id')
                    ->where(fn ($query) => $query->where(
                        'departement_id',
                        $request->input('departement_id')
                    )),
            ],

            'adresse' => ['nullable', 'string', 'max:255'],
            'statut' => ['required', 'in:actif,inactif'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule existe déjà.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'date_embauche.required' => 'La date d’embauche est obligatoire.',
            'departement_id.required' => 'Veuillez sélectionner un département.',
            'departement_id.exists' => 'Le département sélectionné est introuvable.',
            'poste_id.required' => 'Veuillez sélectionner un poste.',
            'poste_id.exists' => 'Ce poste n’appartient pas au département sélectionné.',
            'statut.required' => 'Veuillez sélectionner un statut.',
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