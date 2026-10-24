<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\TypeConge;
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
     * Calcule le nombre de jours ouvrés entre deux dates,
     * en excluant les week-ends et les jours fériés chômés.
     */
    private function calculerJoursOuvres(
        Carbon $debut,
        Carbon $fin
    ): int {
        // Récupérer les jours fériés chômés actifs sur la période.
        $joursFeries = DB::table('jours_feries')
            ->whereBetween('date', [
                $debut->toDateString(),
                $fin->toDateString(),
            ])
            ->where('est_chome', true)
            ->where('statut', 'actif')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $nombreJours = 0;

        // Parcourir toutes les dates, début et fin inclus.
        foreach (CarbonPeriod::create(
            $debut->copy()->startOfDay(),
            $fin->copy()->startOfDay()
        ) as $date) {
            // ISO : samedi = 6, dimanche = 7.
            if ($date->isWeekend()) {
                continue;
            }

            // Ne pas compter un jour férié chômé actif.
            if ($joursFeries->has($date->toDateString())) {
                continue;
            }

            $nombreJours++;
        }

        return $nombreJours;
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
     */
    public function store(Request $request)
    {
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
        $nombreJours = $this->calculerJoursOuvres(
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

        // Enregistrer la demande dans une transaction.
        DB::transaction(function () use ($donnees) {
            DemandeConge::create($donnees);
        });

        return redirect()
            ->route('employe.conges.index')
            ->with(
                'success',
                'Votre demande a été envoyée pour validation.'
            );
    }
}
