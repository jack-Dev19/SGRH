<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Salaire;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * SalaireController (Gestion)
 * ---------------------------
 * CRUD des salaires (partagé Admin + RH).
 */
class SalaireController extends Controller
{
    /**
     * Liste des salaires avec filtres.
     */
    public function index(Request $request): View
    {
        $query = Salaire::with('employe');

        // Recherche par employé
        if ($search = $request->input('search')) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $salaires = $query->orderByDesc('date_debut')->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total'          => Salaire::count(),
            'actifs'         => Salaire::where('statut', 'actif')->count(),
            'masse_salariale'=> Salaire::where('statut', 'actif')->sum('salaire_base'),
            'salaire_moyen'  => Salaire::where('statut', 'actif')->avg('salaire_base') ?? 0,
        ];

        return view('gestion.salaires.index', compact('salaires', 'stats'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.salaires.create', compact('employes'));
    }

    /**
     * Enregistrer un nouveau salaire.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'   => ['required', 'exists:employes,id'],
            'salaire_base' => ['required', 'numeric', 'min:0'],
            'date_debut'   => ['required', 'date'],
            'date_fin'     => ['nullable', 'date', 'after_or_equal:date_debut'],
            'motif'        => ['nullable', 'string', 'max:255'],
            'statut'       => ['required', 'in:actif,inactif'],
            'commentaire'  => ['nullable', 'string', 'max:255'],
        ]);

        Salaire::create($validated);

        return redirect()
            ->route('gestion.salaires.index')
            ->with('success', 'Salaire créé avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $salaire = Salaire::with('employe')->findOrFail($id);

        return view('gestion.salaires.show', compact('salaire'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $salaire  = Salaire::findOrFail($id);
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.salaires.edit', compact('salaire', 'employes'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $salaire = Salaire::findOrFail($id);

        $validated = $request->validate([
            'employe_id'   => ['required', 'exists:employes,id'],
            'salaire_base' => ['required', 'numeric', 'min:0'],
            'date_debut'   => ['required', 'date'],
            'date_fin'     => ['nullable', 'date', 'after_or_equal:date_debut'],
            'motif'        => ['nullable', 'string', 'max:255'],
            'statut'       => ['required', 'in:actif,inactif'],
            'commentaire'  => ['nullable', 'string', 'max:255'],
        ]);

        $salaire->update($validated);

        return redirect()
            ->route('gestion.salaires.index')
            ->with('success', 'Salaire modifié avec succès.');
    }

    /**
     * Supprimer un salaire.
     */
    public function destroy(string $id)
    {
        $salaire = Salaire::findOrFail($id);
        $salaire->delete();

        return redirect()
            ->route('gestion.salaires.index')
            ->with('success', 'Salaire supprimé.');
    }
}