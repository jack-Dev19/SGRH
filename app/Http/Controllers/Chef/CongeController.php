<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

/**
 * CongeController (Chef de département)
 * -------------------------------------
 * Le chef peut consulter et traiter uniquement les congés
 * des employés de son propre département.
 */
class CongeController extends Controller
{
    /**
     * Récupère les identifiants des employés du département
     * auquel appartient le chef connecté.
     *
     * Un chef sans département ne doit accéder à aucune équipe.
     */
    private function getEmployeIds(): Collection
    {
        // Retrouver le profil employé associé au compte connecté.
        $employe = Employe::where('user_id', Auth::id())
            ->firstOrFail();

        // Refuser l'accès si aucun département n'est affecté.
        if (!$employe->departement_id) {
            abort(
                403,
                'Aucun département n’est associé à votre compte.'
            );
        }

        // Récupérer uniquement les employés de ce département.
        return Employe::where(
            'departement_id',
            $employe->departement_id
        )->pluck('id');
    }

    /**
     * Affiche les demandes de congé du département.
     */
    public function index(Request $request): View
    {
        $employeIds = $this->getEmployeIds();

        // Charger les relations utiles à l'affichage.
        $query = DemandeConge::with([
            'employe',
            'typeConge',
            'validateur',
        ])->whereIn('employe_id', $employeIds);

        // Filtrer les demandes par statut si nécessaire.
        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        // Pagination en conservant les filtres sélectionnés.
        $conges = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Statistiques limitées au département du chef.
        $stats = [
            'total' => DemandeConge::whereIn(
                'employe_id',
                $employeIds
            )->count(),

            'en_attente' => DemandeConge::whereIn(
                'employe_id',
                $employeIds
            )->where('statut', 'en_attente')->count(),

            'approuve' => DemandeConge::whereIn(
                'employe_id',
                $employeIds
            )->where('statut', 'approuve')->count(),

            'refuse' => DemandeConge::whereIn(
                'employe_id',
                $employeIds
            )->where('statut', 'refuse')->count(),
        ];

        return view('chef.conges.index', compact(
            'conges',
            'stats'
        ));
    }

    /**
     * Affiche une demande appartenant au département du chef.
     */
    public function show(string $id): View
    {
        $employeIds = $this->getEmployeIds();

        $conge = DemandeConge::with([
            'employe',
            'typeConge',
            'validateur',
        ])
            ->whereIn('employe_id', $employeIds)
            ->findOrFail($id);

        return view('chef.conges.show', compact('conge'));
    }

    /**
     * Approuve une demande encore en attente.
     */
    public function approuver(string $id): RedirectResponse
    {
        $employeIds = $this->getEmployeIds();

        // La demande doit appartenir au département du chef.
        $conge = DemandeConge::with('employe')
            ->whereIn('employe_id', $employeIds)
            ->findOrFail($id);

        // Empêcher une seconde décision sur une demande traitée.
        if ($conge->statut !== 'en_attente') {
            return redirect()->back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }

        // Enregistrer la décision et son auteur.
        $conge->update([
            'statut' => 'approuve',
            'valide_par' => Auth::id(),
            'date_validation' => now(),
            'commentaire_validation' =>
                'Approuvé par le chef de département.',
        ]);

        return redirect()->back()->with(
            'success',
            "Congé de {$conge->employe?->nom_complet} approuvé."
        );
    }

    
    /**
     * Refuse une demande encore en attente.
     */
    public function refuser(
        Request $request,
        string $id
    ): RedirectResponse {
        $employeIds = $this->getEmployeIds();
    
        // Vérifier que la demande appartient au département du chef.
        $conge = DemandeConge::with('employe')
            ->whereIn('employe_id', $employeIds)
            ->findOrFail($id);
    
        // Empêcher de traiter une demande déjà décidée.
        if ($conge->statut !== 'en_attente') {
            return redirect()->back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }
    
        // Valider le commentaire : il reste facultatif.
        $validated = $request->validate([
            'commentaire_validation' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);
    
        // Utiliser le commentaire par défaut si le champ est vide
        // ou si aucun commentaire n'a été envoyé.
        $commentaire = trim(
            $validated['commentaire_validation'] ?? ''
        );
    
        if ($commentaire === '') {
            $commentaire = 'Refusé par le chef de département.';
        }
    
        // Enregistrer le refus.
        $conge->update([
            'statut' => 'refuse',
            'valide_par' => Auth::id(),
            'date_validation' => now(),
            'commentaire_validation' => $commentaire,
        ]);
    
        return redirect()->back()->with(
            'success',
            "Congé de {$conge->employe?->nom_complet} refusé."
        );
    }

}
