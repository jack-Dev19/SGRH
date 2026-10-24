<?php

namespace App\Http\Controllers\Gestion;

use App\Helpers\RelationHelper;
use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Poste;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosteController extends Controller
{
    // =================================================================
    // INDEX
    // =================================================================

    public function index(Request $request): View
    {
        $query = Poste::with('departement')->withCount('employes');

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        if ($departementId = $request->input('departement')) {
            $query->where('departement_id', $departementId);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        $postes       = $query->orderBy('nom')->paginate(15)->withQueryString();
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.index', compact('postes', 'departements'));
    }

    // =================================================================
    // CREATE
    // =================================================================

    public function create(): View
    {
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.create', compact('departements'));
    }

    // =================================================================
    // STORE
    // =================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:500'],
            'departement_id' => ['required', 'exists:departements,id'],
            'salaire_base'   => ['nullable', 'numeric', 'min:0'],
            'statut'         => ['nullable', 'boolean'],
        ]);

        $validated['statut'] = $validated['statut'] ?? 1;

        // ⭐ Éviter les doublons
        $existe = Poste::where('departement_id', $validated['departement_id'])
            ->where('nom', $validated['nom'])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nom' => 'Ce poste existe déjà dans ce département.']);
        }

        try {
            Poste::create($validated);

            return redirect()
                ->route('gestion.postes.index')
                ->with('success', "Poste {$validated['nom']} créé avec succès.");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Création du poste {$validated['nom']}",
                "Impossible de créer le poste. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // SHOW
    // =================================================================

    public function show(string $id): View
    {
        $poste = Poste::with(['departement', 'employes'])
            ->withCount('employes')
            ->findOrFail($id);

        return view('gestion.postes.show', compact('poste'));
    }

    // =================================================================
    // EDIT
    // =================================================================

    public function edit(string $id): View
    {
        $poste        = Poste::findOrFail($id);
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.postes.edit', compact('poste', 'departements'));
    }

    // =================================================================
    // UPDATE
    // =================================================================

    public function update(Request $request, string $id)
    {
        $poste = Poste::findOrFail($id);

        $validated = $request->validate([
            'nom'            => ['required', 'string', 'max:150'],
            'description'    => ['nullable', 'string', 'max:500'],
            'departement_id' => ['required', 'exists:departements,id'],
            'salaire_base'   => ['nullable', 'numeric', 'min:0'],
            'statut'         => ['nullable', 'boolean'],
        ]);

        $validated['statut'] = $validated['statut'] ?? 1;

        // ⭐ Éviter les doublons
        $existe = Poste::where('departement_id', $validated['departement_id'])
            ->where('nom', $validated['nom'])
            ->where('id', '!=', $poste->id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors(['nom' => 'Ce poste existe déjà dans ce département.']);
        }

        try {
            $poste->update($validated);

            return redirect()
                ->route('gestion.postes.index')
                ->with('success', 'Poste modifié avec succès.');

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Modification du poste {$poste->nom}",
                "Impossible de modifier le poste. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // DESTROY — ⭐ Désactivation si utilisé
    // =================================================================

    public function destroy(string $id)
    {
        $poste = Poste::withCount('employes')->findOrFail($id);

        // ⭐ Si utilisé → désactiver au lieu de supprimer
        if ($poste->employes_count > 0) {
            try {
                $poste->update(['statut' => 0]);

                return redirect()
                    ->route('gestion.postes.index')
                    ->with('warning', "Le poste <strong>{$poste->nom}</strong> est utilisé par <strong>{$poste->employes_count} employé(s)</strong>. Il a été <strong>désactivé</strong> au lieu d'être supprimé pour préserver l'historique.");

            } catch (QueryException $e) {
                $message = RelationHelper::logAndReturnMessage(
                    $e,
                    "Désactivation du poste {$poste->nom}",
                    "Impossible de désactiver ce poste. Veuillez réessayer."
                );

                return redirect()
                    ->route('gestion.postes.index')
                    ->with('error_delete', [
                        'titre'   => 'Action impossible',
                        'message' => $message,
                        'icone'   => 'error',
                    ]);
            }
        }

        // ⭐ Aucun employé → suppression définitive
        try {
            $nom = $poste->nom;
            $poste->delete();

            return redirect()
                ->route('gestion.postes.index')
                ->with('success', "Poste {$nom} supprimé avec succès.");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Suppression du poste {$poste->nom}",
                "Impossible de supprimer ce poste car il est utilisé par d'autres enregistrements."
            );

            return redirect()
                ->route('gestion.postes.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => $message,
                    'icone'   => 'error',
                ]);
        }
    }

    // =================================================================
    // ⭐ API — Postes par département (pour le formulaire employé)
    // =================================================================

    /**
     * Retourne les postes actifs d'un département en JSON.
     * Route : GET /gestion/api/postes/par-departement/{departementId}
     */
    public function parDepartement(string $departementId)
    {
        try {
            $postes = Poste::where('departement_id', $departementId)
                ->where('statut', 1)
                ->orderBy('nom')
                ->get(['id', 'nom']);

            return response()->json([
                'success' => true,
                'postes'  => $postes,
                'count'   => $postes->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur API postes par département : ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des postes.',
            ], 500);
        }
    }
}