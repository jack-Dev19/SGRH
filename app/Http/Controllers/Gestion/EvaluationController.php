<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Evaluation;
use App\Models\NiveauAppreciation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * EvaluationController (Gestion)
 * ------------------------------
 * CRUD des évaluations de performance (partagé Admin + RH).
 */
class EvaluationController extends Controller
{
    /**
     * Liste des évaluations avec filtres.
     */
    public function index(Request $request): View
    {
        $query = Evaluation::with(['employe', 'niveauAppreciation', 'evaluateur']);

        // Recherche par employé
        if ($search = $request->input('search')) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Filtre type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $evaluations = $query->orderByDesc('date_evaluation')->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total'    => Evaluation::count(),
            'termine'  => Evaluation::where('statut', 'terminee')->count(),
            'en_cours' => Evaluation::where('statut', 'en_cours')->count(),
            'moyenne'  => Evaluation::whereNotNull('score_global')->avg('score_global') ?? 0,
        ];

        $niveaux = NiveauAppreciation::orderBy('ordre')->get();

        return view('gestion.evaluations.index', compact('evaluations', 'stats', 'niveaux'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $employes = Employe::orderBy('nom')->get();
        $niveaux  = NiveauAppreciation::orderBy('ordre')->get();

        return view('gestion.evaluations.create', compact('employes', 'niveaux'));
    }

    /**
     * Enregistrer une nouvelle évaluation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'              => ['required', 'exists:employes,id'],
            'titre'                   => ['required', 'string', 'max:150'],
            'type'                    => ['required', 'in:annuelle,trimestrielle,probatoire,ponctuelle'],
            'periode'                 => ['nullable', 'string', 'max:50'],
            'date_debut'              => ['nullable', 'date'],
            'date_fin'                => ['nullable', 'date', 'after_or_equal:date_debut'],
            'date_evaluation'         => ['required', 'date'],
            'score_global'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'niveau_appreciation_id'  => ['nullable', 'exists:niveaux_appreciation,id'],
            'appreciation'            => ['nullable', 'string', 'max:100'],
            'commentaire'             => ['nullable', 'string'],
            'recommandation'          => ['nullable', 'string'],
            'statut'                  => ['required', 'in:en_cours,terminee,annulee'],
        ]);

        // L'évaluateur est l'utilisateur connecté
        $validated['evaluateur_id'] = Auth::id();

        Evaluation::create($validated);

        return redirect()
            ->route('gestion.evaluations.index')
            ->with('success', 'Évaluation créée avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $evaluation = Evaluation::with(['employe', 'niveauAppreciation', 'evaluateur'])->findOrFail($id);

        return view('gestion.evaluations.show', compact('evaluation'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $evaluation = Evaluation::findOrFail($id);
        $employes   = Employe::orderBy('nom')->get();
        $niveaux    = NiveauAppreciation::orderBy('ordre')->get();

        return view('gestion.evaluations.edit', compact('evaluation', 'employes', 'niveaux'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $evaluation = Evaluation::findOrFail($id);

        $validated = $request->validate([
            'employe_id'              => ['required', 'exists:employes,id'],
            'titre'                   => ['required', 'string', 'max:150'],
            'type'                    => ['required', 'in:annuelle,trimestrielle,probatoire,ponctuelle'],
            'periode'                 => ['nullable', 'string', 'max:50'],
            'date_debut'              => ['nullable', 'date'],
            'date_fin'                => ['nullable', 'date', 'after_or_equal:date_debut'],
            'date_evaluation'         => ['required', 'date'],
            'score_global'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'niveau_appreciation_id'  => ['nullable', 'exists:niveaux_appreciation,id'],
            'appreciation'            => ['nullable', 'string', 'max:100'],
            'commentaire'             => ['nullable', 'string'],
            'recommandation'          => ['nullable', 'string'],
            'statut'                  => ['required', 'in:en_cours,terminee,annulee'],
        ]);

        $evaluation->update($validated);

        return redirect()
            ->route('gestion.evaluations.index')
            ->with('success', 'Évaluation modifiée avec succès.');
    }

    /**
     * Supprimer une évaluation.
     */
    public function destroy(string $id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()
            ->route('gestion.evaluations.index')
            ->with('success', 'Évaluation supprimée.');
    }
}