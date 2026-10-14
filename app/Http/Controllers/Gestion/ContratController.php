<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ContratController (Gestion)
 * ---------------------------
 * CRUD des contrats de travail (partagé Admin + RH).
 */
class ContratController extends Controller
{
    /**
     * Liste des contrats avec filtres.
     */
    public function index(Request $request): View
    {
        $query = Contrat::with('employe');

        // Recherche par numéro
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhereHas('employe', function ($sub) use ($search) {
                      $sub->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filtre par statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $contrats = $query->orderByDesc('date_debut')->paginate(12)->withQueryString();

        // Compteurs
        $stats = [
            'total'   => Contrat::count(),
            'cdi'     => Contrat::where('type', 'CDI')->count(),
            'cdd'     => Contrat::where('type', 'CDD')->count(),
            'actifs'  => Contrat::where('statut', 'actif')->count(),
        ];

        return view('gestion.contrats.index', compact('contrats', 'stats'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.contrats.create', compact('employes'));
    }

    /**
     * Enregistrer un nouveau contrat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'            => ['required', 'exists:employes,id'],
            'numero'                => ['required', 'string', 'max:50', 'unique:contrats,numero'],
            'type'                  => ['required', 'in:CDI,CDD,Stage,Intérim,Freelance'],
            'date_debut'            => ['required', 'date'],
            'date_fin'              => ['nullable', 'date', 'after_or_equal:date_debut'],
            'salaire'               => ['required', 'numeric', 'min:0'],
            'devise'                => ['required', 'string', 'max:10'],
            'periode_essai'         => ['nullable', 'integer', 'min:0'],
            'unite_periode_essai'   => ['nullable', 'string', 'max:20'],
            'statut'                => ['required', 'in:actif,expire,resilie'],
            'description'           => ['nullable', 'string', 'max:255'],
        ]);

        Contrat::create($validated);

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $contrat = Contrat::with('employe')->findOrFail($id);

        return view('gestion.contrats.show', compact('contrat'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $contrat  = Contrat::findOrFail($id);
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.contrats.edit', compact('contrat', 'employes'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $contrat = Contrat::findOrFail($id);

        $validated = $request->validate([
            'employe_id'            => ['required', 'exists:employes,id'],
            'numero'                => ['required', 'string', 'max:50', 'unique:contrats,numero,' . $contrat->id],
            'type'                  => ['required', 'in:CDI,CDD,Stage,Intérim,Freelance'],
            'date_debut'            => ['required', 'date'],
            'date_fin'              => ['nullable', 'date', 'after_or_equal:date_debut'],
            'salaire'               => ['required', 'numeric', 'min:0'],
            'devise'                => ['required', 'string', 'max:10'],
            'periode_essai'         => ['nullable', 'integer', 'min:0'],
            'unite_periode_essai'   => ['nullable', 'string', 'max:20'],
            'statut'                => ['required', 'in:actif,expire,resilie'],
            'description'           => ['nullable', 'string', 'max:255'],
        ]);

        $contrat->update($validated);

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat modifié avec succès.');
    }

    /**
     * Supprimer un contrat.
     */
    public function destroy(string $id)
    {
        $contrat = Contrat::findOrFail($id);
        $contrat->delete();

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }
}