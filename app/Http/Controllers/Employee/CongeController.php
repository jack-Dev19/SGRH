<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\TypeConge;
use App\Models\User;
use App\Services\CalculCongeService;
use App\Services\NotificationService;
use App\Models\Departement;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * CongeController (Employé)
 * -------------------------
 * Gestion des demandes de congé de l'employé connecté.
 *
 * Règles métier :
 * - L'employé ne consulte que ses propres demandes.
 * - Une demande ne peut pas chevaucher une demande en attente
 *   ou déjà approuvée.
 * - Le nombre de jours exclut les week-ends et les jours fériés
 *   chômés actifs enregistrés dans la table jours_feries.
 *
 * Notifications :
 * - Lorsqu'une demande est créée, le Chef du département
 *   reçoit une notification s'il est correctement affecté.
 */
class CongeController extends Controller
{
    /**
     * Récupère le profil employé du compte connecté.
     */
    private function getEmploye(): Employe
    {
        return Employe::where('user_id', Auth::id())
            ->firstOrFail();
    }

    /**
     * Affiche uniquement les demandes de l'employé connecté.
     */
    public function index(): View
    {
        $employe = $this->getEmploye();

        $conges = DemandeConge::with('typeConge')
            ->where('employe_id', $employe->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Statistiques personnelles de l'employé.
        $stats = [
            'total' => DemandeConge::where(
                'employe_id',
                $employe->id
            )->count(),

            'en_attente' => DemandeConge::where(
                'employe_id',
                $employe->id
            )->where('statut', 'en_attente')->count(),

            'approuve' => DemandeConge::where(
                'employe_id',
                $employe->id
            )->where('statut', 'approuve')->count(),

            'refuse' => DemandeConge::where(
                'employe_id',
                $employe->id
            )->where('statut', 'refuse')->count(),
        ];

        return view(
            'employee.conges.index',
            compact('conges', 'stats')
        );
    }

    /**
     * Affiche le formulaire de nouvelle demande.
     */
    public function create(): View
    {
        $typesConges = TypeConge::orderBy('nom')->get();

        return view(
            'employee.conges.create',
            compact('typesConges')
        );
    }

    /**
     * Enregistre une nouvelle demande après vérification
     * des dates, des chevauchements et des jours ouvrés.
     *
     * Une notification est également envoyée au Chef
     * responsable du département de l'employé.
     */
    public function store(
        Request $request,
        CalculCongeService $calculCongeService,
        NotificationService $notificationService
    ) {
        $employe = $this->getEmploye();

        // Valider les données reçues du formulaire.
        $validated = $request->validate([
            'type_conge_id' => [
                'required',
                'exists:types_conges,id',
            ],

            'date_debut' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],

            'motif' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        // Normaliser les dates pour les comparaisons.
        $debut = Carbon::parse($validated['date_debut'])
            ->startOfDay();

        $fin = Carbon::parse($validated['date_fin'])
            ->startOfDay();

        /*
         * Vérifier le chevauchement :
         * une demande existante chevauche la nouvelle si
         * son début est avant la fin de la nouvelle demande
         * ET si sa fin est après le début de la nouvelle.
         *
         * Les demandes refusées ne bloquent pas la période.
         */
        $chevauchement = DemandeConge::where(
            'employe_id',
            $employe->id
        )
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->whereDate('date_debut', '<=', $fin->toDateString())
            ->whereDate('date_fin', '>=', $debut->toDateString())
            ->exists();

        if ($chevauchement) {
            throw ValidationException::withMessages([
                'date_debut' =>
                    'Cette période chevauche une demande de congé '
                    . 'en attente ou déjà approuvée. '
                    . 'Veuillez choisir d’autres dates.',
            ]);
        }

        /*
         * Calculer les jours ouvrés :
         * - du lundi au vendredi ;
         * - sans les jours fériés chômés actifs.
         */
        $nombreJours = $calculCongeService->calculerJoursOuvres(
            $debut,
            $fin
        );

        // Refuser une période qui ne contient aucun jour ouvré.
        if ($nombreJours === 0) {
            throw ValidationException::withMessages([
                'date_debut' =>
                    'La période choisie ne contient aucun jour ouvré. '
                    . 'Veuillez choisir d’autres dates.',
            ]);
        }

        /*
         * Construire les données à enregistrer côté serveur.
         * L'employé, le statut et le nombre de jours ne viennent
         * jamais directement du formulaire.
         */
        $donnees = [
            'employe_id' => $employe->id,
            'type_conge_id' => $validated['type_conge_id'],
            'date_debut' => $debut->toDateString(),
            'date_fin' => $fin->toDateString(),
            'nombre_jours' => $nombreJours,
            'motif' => $validated['motif'] ?? null,
            'statut' => 'en_attente',
        ];

        /*
         * Enregistrer la demande et créer sa notification
         * dans une même transaction.
         *
         * Si aucun Chef n'est affecté au département,
         * la demande est quand même enregistrée.
         */
        
        DB::transaction(function () use (
            $donnees,
            $employe,
            $debut,
            $fin,
            $notificationService
        ) {
            // 1. Enregistrer la demande de congé.
            $conge = DemandeConge::create($donnees);
                
            // 2. Rechercher le département et son responsable.
            $departement = Departement::with('responsable.role')
                ->find($employe->departement_id);
                
            $chef = $departement?->responsable;
                
            // 3. Préparer le message de notification.
            $message = 'Une nouvelle demande de congé a été soumise par '
                . $employe->nom_complet
                . ', du '
                . $debut->format('d/m/Y')
                . ' au '
                . $fin->format('d/m/Y')
                . '.';
                    
            // 4. Notifier le Chef du département.
            if ($chef && $chef->hasRole('Chef de département')) {
                $notificationService->envoyer(
                    $chef,
                    'Nouvelle demande de congé',
                    $message,
                    'info',
                    route('chef.conges.index'),
                    'conges',
                    $conge->id
                );
            }
                    
            // 5. Rechercher les RH et Administrateurs actifs.
            $destinataires = $notificationService->utilisateursParRoles([
                'Responsable RH',
                'Administrateur',
            ]);
                    
            // 6. Notifier les RH et Administrateurs.
            $notificationService->envoyerAPlusieurs(
                $destinataires,
                'Nouvelle demande de congé',
                $message,
                'info',
                route('gestion.conges.index'),
                'conges',
                $conge->id
            );
        });

        // Retourner vers la liste des demandes de l'employé.
        return redirect()->route('employe.conges.index')->with(
            'success',
            'Votre demande a été envoyée pour validation.'
        );
    }
}
