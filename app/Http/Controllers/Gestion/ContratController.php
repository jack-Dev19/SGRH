<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use App\Models\Employe;
use App\Services\ContratPdfService;
use App\Services\FichePaieService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * ContratController (Gestion)
 * ---------------------------
 * CRUD des contrats + génération PDF (contrat + fiche de paie).
 * Partagé entre Administrateur et Responsable RH.
 */
class ContratController extends Controller
{
    // =================================================================
    // 1. LISTE — Index avec filtres + statistiques
    // =================================================================

    public function index(Request $request): View
    {
        $query = Contrat::with('employe');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhereHas('employe', function ($sub) use ($search) {
                      $sub->where('nom', 'like', "%{$search}%")
                          ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $contrats = $query->orderByDesc('date_debut')->paginate(12)->withQueryString();

        $stats = [
            'total'  => Contrat::count(),
            'cdi'    => Contrat::where('type', 'CDI')->count(),
            'cdd'    => Contrat::where('type', 'CDD')->count(),
            'actifs' => Contrat::where('statut', 'actif')->count(),
        ];

        return view('gestion.contrats.index', compact('contrats', 'stats'));
    }

    // =================================================================
    // 2. CRÉATION
    // =================================================================

    public function create(): View
    {
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.contrats.create', compact('employes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'          => ['required', 'exists:employes,id'],
            'numero'              => ['required', 'string', 'max:50', 'unique:contrats,numero'],
            'type'                => ['required', 'in:CDI,CDD,Stage,Intérim,Freelance'],
            'date_debut'          => ['required', 'date'],
            'date_fin'            => ['nullable', 'date', 'after_or_equal:date_debut'],
            'salaire'             => ['required', 'numeric', 'min:0'],
            'devise'              => ['required', 'string', 'max:10'],
            'periode_essai'       => ['nullable', 'integer', 'min:0'],
            'unite_periode_essai' => ['nullable', 'string', 'max:20'],
            'statut'              => ['required', 'in:actif,expire,resilie'],
            'description'         => ['nullable', 'string', 'max:255'],
        ], [
            'employe_id.required' => 'Veuillez sélectionner un employé.',
            'type.required'       => 'Veuillez sélectionner un type de contrat.',
        ]);

        Contrat::create($validated);

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    // =================================================================
    // 3. AFFICHAGE
    // =================================================================

    public function show(string $id): View
    {
        $contrat = Contrat::with('employe.departement', 'employe.poste')->findOrFail($id);

        return view('gestion.contrats.show', compact('contrat'));
    }

    // =================================================================
    // 4. MODIFICATION
    // =================================================================

    public function edit(string $id): View
    {
        $contrat  = Contrat::findOrFail($id);
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.contrats.edit', compact('contrat', 'employes'));
    }

    public function update(Request $request, string $id)
    {
        $contrat = Contrat::findOrFail($id);

        $validated = $request->validate([
            'employe_id'          => ['required', 'exists:employes,id'],
            'numero'              => ['required', 'string', 'max:50', 'unique:contrats,numero,' . $contrat->id],
            'type'                => ['required', 'in:CDI,CDD,Stage,Intérim,Freelance'],
            'date_debut'          => ['required', 'date'],
            'date_fin'            => ['nullable', 'date', 'after_or_equal:date_debut'],
            'salaire'             => ['required', 'numeric', 'min:0'],
            'devise'              => ['required', 'string', 'max:10'],
            'periode_essai'       => ['nullable', 'integer', 'min:0'],
            'unite_periode_essai' => ['nullable', 'string', 'max:20'],
            'statut'              => ['required', 'in:actif,expire,resilie'],
            'description'         => ['nullable', 'string', 'max:255'],
        ]);

        $contrat->update($validated);

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat modifié avec succès.');
    }

    // =================================================================
    // 5. SUPPRESSION
    // =================================================================

    public function destroy(string $id)
    {
        $contrat = Contrat::findOrFail($id);
        $contrat->delete();

        return redirect()
            ->route('gestion.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    // =================================================================
    // 6. TÉLÉCHARGEMENT PDF DU CONTRAT
    // =================================================================

    /**
     * Télécharger le contrat PDF.
     * Route : GET /gestion/contrats/{id}/download
     */
    public function downloadPdf(string $id, ContratPdfService $pdfService)
    {
        $contrat = Contrat::with('employe.departement', 'employe.poste')->findOrFail($id);

        try {
            return $pdfService->download($contrat);
        } catch (\Exception $e) {
            return redirect()
                ->route('gestion.contrats.show', $contrat)
                ->with('error', $e->getMessage());
        }
    }

    // =================================================================
    // 7. TÉLÉCHARGEMENT FICHE DE PAIE
    // =================================================================

    /**
     * Télécharger la fiche de paie PDF.
     * Route : GET /gestion/contrats/{id}/fiche-paie?mois=X&annee=Y
     */
    public function fichePaie(string $id, Request $request, FichePaieService $fichePaieService)
    {
        $contrat = Contrat::with('employe.departement', 'employe.poste')->findOrFail($id);

        // Valeurs par défaut : mois et année en cours
        $mois  = (int) $request->input('mois',  now()->month);
        $annee = (int) $request->input('annee', now()->year);

        // Validation
        if ($mois < 1 || $mois > 12) {
            $mois = now()->month;
        }
        if ($annee < 2000 || $annee > 2100) {
            $annee = now()->year;
        }

        try {
            return $fichePaieService->download($contrat, $mois, $annee);
        } catch (\Exception $e) {
            return redirect()
                ->route('gestion.contrats.show', $contrat)
                ->with('error', $e->getMessage());
        }
    }
}