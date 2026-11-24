<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\BulletinPaie;
use App\Models\Contrat;
use App\Models\User;
use App\Services\BulletinPaieService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BulletinPaieController extends Controller
{
    /**
     * Liste des bulletins.
     */
    public function index(Request $request): View
    {
        $query = BulletinPaie::with('employe');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        $bulletins = $query
            ->orderByDesc('annee')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => BulletinPaie::count(),
            'brouillons' => BulletinPaie::where('statut', 'brouillon')->count(),
            'publies' => BulletinPaie::where('statut', 'publie')->count(),
        ];

        return view('gestion.bulletins-paie.index', compact(
            'bulletins',
            'stats'
        ));
    }

    /**
     * Formulaire de génération.
     */
    public function create(): View
    {
        $contrats = Contrat::with('employe')
            ->where('statut', 'actif')
            ->whereHas('employe')
            ->orderBy('numero')
            ->get();

        return view('gestion.bulletins-paie.create', compact('contrats'));
    }

    /**
     * Générer un bulletin en brouillon.
     */
    public function store(
        Request $request,
        BulletinPaieService $bulletinPaieService
    ): RedirectResponse {
        $validated = $request->validate([
            'contrat_id' => ['required', 'exists:contrats,id'],
            'mois' => ['required', 'integer', 'between:1,12'],
            'annee' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $contrat = Contrat::findOrFail($validated['contrat_id']);

        $bulletin = $bulletinPaieService->generer(
            $contrat,
            (int) $validated['mois'],
            (int) $validated['annee']
        );

        return redirect()
            ->route('gestion.bulletins-paie.show', $bulletin->id)
            ->with('success', 'Bulletin généré en brouillon avec succès.');
    }

    /**
     * Consulter un bulletin.
     */
    public function show(string $id): View
    {
        $bulletin = BulletinPaie::with([
            'employe',
            'lignes',
        ])->findOrFail($id);

        return view('gestion.bulletins-paie.show', compact('bulletin'));
    }

    /**
     * Publier un bulletin et notifier l'employé.
     */
    public function publier(
        string $id,
        NotificationService $notificationService
    ): RedirectResponse {
        $bulletin = BulletinPaie::with('employe')->findOrFail($id);

        if ($bulletin->statut === 'publie') {
            return back()->with('info', 'Ce bulletin est déjà publié.');
        }

        if ($bulletin->statut !== 'brouillon') {
            return back()->with('error', 'Ce bulletin ne peut pas être publié.');
        }

        DB::transaction(function () use ($bulletin) {
            $bulletin->update([
                'statut' => 'publie',
            ]);
        });

        // Retrouver le compte utilisateur de l'employé.
        $user = $bulletin->employe
            ? User::find($bulletin->employe->user_id)
            : null;

        // La notification est envoyée uniquement après publication.
        if ($user) {
            $notificationService->envoyer(
                $user,
                'Bulletin de paie disponible',
                "Votre bulletin de paie de {$bulletin->periode} {$bulletin->annee} est disponible dans votre espace employé.",
                'success',
                null,
                'paie'
            );
        }

        return redirect()
            ->route('gestion.bulletins-paie.show', $bulletin->id)
            ->with('success', 'Bulletin publié avec succès.');
    }
}
