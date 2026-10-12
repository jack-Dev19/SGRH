<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * LISTE des employés (avec recherche + filtres)
     */
    public function index(Request $request)
    {
        // =============================================
        // On construit la requête avec les filtres
        // =============================================
        $query = Employee::with('departement');

        // Recherche (nom, prénom, matricule, email)
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%$s%")
                  ->orWhere('prenom', 'like', "%$s%")
                  ->orWhere('matricule', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }

        // Filtre par département
        if ($request->filled('departement')) {
            $query->where('departement_id', $request->departement);
        }

        // Filtre par statut (actif / inactif)
        if ($request->filled('statut')) {
            $query->where('actif', $request->statut === 'actif');
        }

        // Tri + pagination
        $employees = $query->orderBy('nom')->paginate(10)->withQueryString();

        // Liste des départements pour le filtre
        $departements = Departement::orderBy('nom')->get();

        return view('admin.employees.index', compact('employees', 'departements'));
    }

    /**
     * FORMULAIRE de création
     */
    public function create()
    {
        $departements = Departement::orderBy('nom')->get();
        return view('admin.employees.create', compact('departements'));
    }

    /**
     * ENREGISTRER un nouvel employé
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule'      => 'required|string|unique:employees,matricule|max:20',
            'nom'            => 'required|string|max:50',
            'prenom'         => 'required|string|max:50',
            'sexe'           => 'required|in:M,F',
            'email'          => 'required|email|unique:employees,email',
            'telephone'      => 'nullable|string|max:20',
            'salaire_base'   => 'required|numeric|min:0',
            'date_embauche'  => 'required|date',
            'departement_id' => 'required|exists:departements,id',
            'actif'          => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        Employee::create($validated);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employé ajouté avec succès !');
    }

    /**
     * VUE DÉTAIL d'un employé
     */
    public function show(Employee $employee)
    {
        $employee->load('departement', 'conges');
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * FORMULAIRE de modification
     */
    public function edit(Employee $employee)
    {
        $departements = Departement::orderBy('nom')->get();
        return view('admin.employees.edit', compact('employee', 'departements'));
    }

    /**
     * METTRE À JOUR un employé
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'matricule'      => 'required|string|max:20|unique:employees,matricule,' . $employee->id,
            'nom'            => 'required|string|max:50',
            'prenom'         => 'required|string|max:50',
            'sexe'           => 'required|in:M,F',
            'email'          => 'required|email|unique:employees,email,' . $employee->id,
            'telephone'      => 'nullable|string|max:20',
            'salaire_base'   => 'required|numeric|min:0',
            'date_embauche'  => 'required|date',
            'departement_id' => 'required|exists:departements,id',
            'actif'          => 'boolean',
        ]);

        $validated['actif'] = $request->has('actif');

        $employee->update($validated);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employé modifié avec succès !');
    }

    /**
     * SUPPRIMER un employé
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employé supprimé avec succès !');
    }
}