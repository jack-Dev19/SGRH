<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\OffreEmploi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OffreEmploiController extends Controller
{
    // =================================================================
    // 1. LISTE
    // =================================================================

    public function index(Request $request)
    {
        $query = OffreEmploi::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        if ($type = $request->input('type_contrat')) {
            $query->where('type_contrat', $type);
        }

        $offres = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'     => OffreEmploi::count(),
            'publiees'  => OffreEmploi::where('statut', 'publiee')->count(),
            'brouillon' => OffreEmploi::where('statut', 'brouillon')->count(),
            'archivees' => OffreEmploi::where('statut', 'archivee')->count(),
        ];

        return view('gestion.offres.index', compact('offres', 'stats'));
    }

    // =================================================================
    // 2. CRÉATION
    // =================================================================

    public function create()
    {
        return view('gestion.offres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'            => ['required', 'string', 'max:150'],
            'description'      => ['required', 'string'],
            'missions'         => ['nullable', 'string'],
            'profil_recherche' => ['nullable', 'string'],
            'type_contrat'     => ['required', 'in:CDI,CDD,Stage,Freelance,Intérim'],
            'lieu'             => ['required', 'string', 'max:100'],
            'experience'       => ['nullable', 'string', 'max:50'],
            'niveau_etude'     => ['nullable', 'string', 'max:50'],
            'salaire'          => ['nullable', 'string', 'max:50'],
            'email_contact'    => ['required', 'email'],
            'date_limite'      => ['nullable', 'date', 'after_or_equal:today'],   // ⭐ Renommé
            'statut'           => ['required', 'in:brouillon,publiee,archivee'],
        ], [
            'titre.required'         => 'Le titre du poste est obligatoire.',
            'description.required'   => 'La description est obligatoire.',
            'type_contrat.required'  => 'Le type de contrat est obligatoire.',
            'lieu.required'          => 'Le lieu est obligatoire.',
            'email_contact.required' => 'L\'email de contact est obligatoire.',
            'email_contact.email'    => 'L\'email doit être valide.',
            'date_limite.after_or_equal' => 'La date limite doit être aujourd\'hui ou dans le futur.',
        ]);

        $validated['slug']       = Str::slug($validated['titre']) . '-' . Str::random(6);
        $validated['created_by'] = auth()->id();

        if ($validated['statut'] === 'publiee') {
            $validated['publiee_le'] = now();
        }

        OffreEmploi::create($validated);

        return redirect()
            ->route('gestion.offres.index')
            ->with('success', 'Offre créée avec succès.');
    }

    // =================================================================
    // 3. AFFICHAGE
    // =================================================================

    public function show(OffreEmploi $offre)
    {
        return view('gestion.offres.show', compact('offre'));
    }

    // =================================================================
    // 4. MODIFICATION
    // =================================================================

    public function edit(OffreEmploi $offre)
    {
        return view('gestion.offres.edit', compact('offre'));
    }

    public function update(Request $request, OffreEmploi $offre)
    {
        $validated = $request->validate([
            'titre'            => ['required', 'string', 'max:150'],
            'description'      => ['required', 'string'],
            'missions'         => ['nullable', 'string'],
            'profil_recherche' => ['nullable', 'string'],
            'type_contrat'     => ['required', 'in:CDI,CDD,Stage,Freelance,Intérim'],
            'lieu'             => ['required', 'string', 'max:100'],
            'experience'       => ['nullable', 'string', 'max:50'],
            'niveau_etude'     => ['nullable', 'string', 'max:50'],
            'salaire'          => ['nullable', 'string', 'max:50'],
            'email_contact'    => ['required', 'email'],
            'date_limite'      => ['nullable', 'date'],   // ⭐ Renommé
            'statut'           => ['required', 'in:brouillon,publiee,archivee'],
        ]);

        if ($validated['statut'] === 'publiee' && $offre->statut !== 'publiee') {
            $validated['publiee_le'] = now();
        }

        $offre->update($validated);

        return redirect()
            ->route('gestion.offres.index')
            ->with('success', 'Offre modifiée avec succès.');
    }

    // =================================================================
    // 5. SUPPRESSION
    // =================================================================

    public function destroy(OffreEmploi $offre)
    {
        $offre->delete();

        return redirect()
            ->route('gestion.offres.index')
            ->with('success', 'Offre supprimée.');
    }

    // =================================================================
    // 6. ACTIONS RAPIDES
    // =================================================================

    public function publier(OffreEmploi $offre)
    {
        $offre->update([
            'statut'     => 'publiee',
            'publiee_le' => now(),
        ]);

        return back()->with('success', 'Offre publiée sur la page d\'accueil.');
    }

    public function archiver(OffreEmploi $offre)
    {
        $offre->update(['statut' => 'archivee']);

        return back()->with('success', 'Offre archivée.');
    }
}