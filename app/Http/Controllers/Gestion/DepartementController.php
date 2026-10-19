<?php

namespace App\Http\Controllers\Gestion;

use App\Helpers\RelationHelper;
use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Poste;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DepartementController extends Controller
{
    // =================================================================
    // INDEX
    // =================================================================

    public function index(Request $request): View
    {
        $query = Departement::with('responsable')
            ->withCount(['employes', 'postes']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        $departements = $query->orderBy('nom')->paginate(12)->withQueryString();

        return view('gestion.departements.index', compact('departements'));
    }

    // =================================================================
    // CREATE
    // =================================================================

    public function create(): View
    {
        $chefs = User::whereHas('role', function ($q) {
            $q->where('nom', 'Chef de département');
        })->orderBy('name')->get();

        return view('gestion.departements.create', compact('chefs'));
    }

    // =================================================================
    // STORE — Création avec postes associés
    // =================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                  => ['required', 'string', 'max:150', 'unique:departements,nom'],
            'description'          => ['nullable', 'string', 'max:1000'],
            'responsable_id'       => ['nullable', 'exists:users,id'],
            'statut'               => ['required', 'in:0,1'],
            'postes'               => ['nullable', 'array'],
            'postes.*.nom'         => ['required', 'string', 'max:150'],
            'postes.*.description' => ['nullable', 'string', 'max:500'],
        ], [
            'nom.required'          => 'Le nom du département est obligatoire.',
            'nom.unique'            => 'Ce nom de département existe déjà.',
            'postes.*.nom.required' => 'Le nom de chaque poste est obligatoire.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Créer le département
            $departement = Departement::create([
                'nom'            => $validated['nom'],
                'description'    => $validated['description'] ?? null,
                'responsable_id' => $validated['responsable_id'] ?? null,
                'statut'         => $validated['statut'],
            ]);

            // 2. Créer les postes
            $nbPostes = 0;
            if (!empty($validated['postes'])) {
                foreach ($validated['postes'] as $posteData) {
                    if (trim($posteData['nom']) === '') {
                        continue;
                    }

                    Poste::create([
                        'departement_id' => $departement->id,
                        'nom'            => trim($posteData['nom']),
                        'description'    => $posteData['description'] ?? null,
                        'statut'         => 1,
                    ]);
                    $nbPostes++;
                }
            }

            DB::commit();

            return redirect()
                ->route('gestion.departements.index')
                ->with('success', "Département {$departement->nom} créé avec succès ({$nbPostes} poste(s) associé(s)).");

        } catch (QueryException $e) {
            DB::rollBack();

            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Création du département {$validated['nom']}",
                "Impossible de créer le département. Veuillez vérifier les données et réessayer."
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
        $departement = Departement::with([
                'responsable',
                'employes.poste',
                'postes' => function ($q) {
                    $q->withCount('employes')->orderBy('nom');
                },
            ])
            ->withCount(['employes', 'postes'])
            ->findOrFail($id);

        return view('gestion.departements.show', compact('departement'));
    }

    // =================================================================
    // EDIT
    // =================================================================

    public function edit(string $id): View
    {
        $departement = Departement::with('postes')->findOrFail($id);

        $chefs = User::whereHas('role', function ($q) {
            $q->where('nom', 'Chef de département');
        })->orderBy('name')->get();

        return view('gestion.departements.edit', compact('departement', 'chefs'));
    }

    // =================================================================
    // UPDATE — Modification avec gestion des postes
    // =================================================================

    public function update(Request $request, string $id)
    {
        $departement = Departement::findOrFail($id);

        $validated = $request->validate([
            'nom'                  => ['required', 'string', 'max:150', 'unique:departements,nom,' . $departement->id],
            'description'          => ['nullable', 'string', 'max:1000'],
            'responsable_id'       => ['nullable', 'exists:users,id'],
            'statut'               => ['required', 'in:0,1'],
            'postes'               => ['nullable', 'array'],
            'postes.*.id'          => ['nullable', 'exists:postes,id'],
            'postes.*.nom'         => ['required', 'string', 'max:150'],
            'postes.*.description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $departement->update([
                'nom'            => $validated['nom'],
                'description'    => $validated['description'] ?? null,
                'responsable_id' => $validated['responsable_id'] ?? null,
                'statut'         => $validated['statut'],
            ]);

            // IDs des postes soumis
            $postesSoumisIds = collect($validated['postes'] ?? [])
                ->pluck('id')
                ->filter()
                ->toArray();

            // Postes retirés du formulaire
            $postesASupprimer = Poste::where('departement_id', $departement->id)
                ->whereNotIn('id', $postesSoumisIds)
                ->get();

            foreach ($postesASupprimer as $poste) {
                if ($poste->employes()->count() > 0) {
                    // Désactiver au lieu de supprimer
                    $poste->update(['statut' => 0]);
                } else {
                    $poste->delete();
                }
            }

            // Mettre à jour / créer les postes
            foreach ($validated['postes'] ?? [] as $posteData) {
                if (trim($posteData['nom']) === '') {
                    continue;
                }

                if (!empty($posteData['id'])) {
                    Poste::where('id', $posteData['id'])
                        ->where('departement_id', $departement->id)
                        ->update([
                            'nom'         => trim($posteData['nom']),
                            'description' => $posteData['description'] ?? null,
                        ]);
                } else {
                    Poste::create([
                        'departement_id' => $departement->id,
                        'nom'            => trim($posteData['nom']),
                        'description'    => $posteData['description'] ?? null,
                        'statut'         => 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('gestion.departements.index')
                ->with('success', 'Département et postes mis à jour avec succès.');

        } catch (QueryException $e) {
            DB::rollBack();

            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Modification du département {$departement->nom}",
                "Impossible de modifier le département. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // DESTROY — Suppression avec vérification COMPLÈTE
    // =================================================================

    public function destroy(string $id)
    {
        $departement = Departement::withCount('employes')->findOrFail($id);

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 1 : Vérifier les employés
        // ═══════════════════════════════════════════════════════════════
        if ($departement->employes_count > 0) {
            return redirect()
                ->route('gestion.departements.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => "Le département <strong>{$departement->nom}</strong> contient <strong>{$departement->employes_count} employé(s)</strong>. Réaffectez-les d'abord à un autre département avant de supprimer celui-ci.",
                    'icone'   => 'error',
                ]);
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 2 : Vérifier les postes utilisés
        // ═══════════════════════════════════════════════════════════════
        $postesUtilises = Poste::where('departement_id', $departement->id)
            ->withCount('employes')
            ->having('employes_count', '>', 0)
            ->get();

        if ($postesUtilises->isNotEmpty()) {
            $nbPostes    = $postesUtilises->count();
            $nomsPostes  = $postesUtilises->pluck('nom')->take(3)->join(', ');
            $suffixe     = $nbPostes > 3 ? '...' : '';

            return redirect()
                ->route('gestion.departements.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => "Le département <strong>{$departement->nom}</strong> possède <strong>{$nbPostes} poste(s)</strong> encore utilisés par des employés ({$nomsPostes}{$suffixe}). Veuillez d'abord les désactiver ou réaffecter les employés.",
                    'icone'   => 'error',
                ]);
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 3 : Suppression
        // ═══════════════════════════════════════════════════════════════
        try {
            DB::beginTransaction();

            // Supprimer les postes non utilisés
            Poste::where('departement_id', $departement->id)->delete();

            $nom = $departement->nom;
            $departement->delete();

            DB::commit();

            return redirect()
                ->route('gestion.departements.index')
                ->with('success', "Département {$nom} et ses postes supprimés avec succès.");

        } catch (QueryException $e) {
            DB::rollBack();

            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Suppression du département {$departement->nom}",
                "Impossible de supprimer ce département car des éléments y sont encore liés. Veuillez d'abord les traiter."
            );

            return redirect()
                ->route('gestion.departements.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => $message,
                    'icone'   => 'error',
                ]);
        }
    }
}