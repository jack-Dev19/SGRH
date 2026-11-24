<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\HistoriqueAction;
use App\Models\Presence;
use App\Services\NotificationService;
use App\Services\PointageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * PresenceController (Gestion)
 * ----------------------------
 * CRUD des présences (partagé Admin + RH).
 */
class PresenceController extends Controller
{
    /**
     * Liste des présences avec filtres.
     */
    public function index(Request $request): View
    {
        $query = Presence::with('employe');

        // Recherche par employé
        if ($search = $request->input('search')) {
            $query->whereHas('employe', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        // Filtre date
        if ($date = $request->input('date')) {
            $query->whereDate('date', $date);
        }

        $presences = $query->orderByDesc('date')->paginate(15)->withQueryString();

        // Stats du jour
        $today = now()->toDateString();
        $stats = [
            'total'     => Presence::count(),
            'aujourdhui'=> Presence::whereDate('date', $today)->count(),
            'present'   => Presence::whereDate('date', $today)->where('statut', 'present')->count(),
            'absent'    => Presence::whereDate('date', $today)->where('statut', 'absent')->count(),
        ];

        $employes = Employe::orderBy('nom')->get();

        return view('gestion.presences.index', compact('presences', 'stats', 'employes'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.presences.create', compact('employes'));
    }

    /**
     * Enregistrer une nouvelle présence.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employe_id'         => ['required', 'exists:employes,id'],
            'date'               => ['required', 'date'],
            'heure_entree'       => ['nullable', 'date_format:H:i'],
            'heure_sortie'       => ['nullable', 'date_format:H:i', 'after:heure_entree'],
            'statut'             => ['required', 'in:present,absent,retard,conge'],
            'retard_minutes'     => ['nullable', 'integer', 'min:0'],
            'heures_travaillees' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'commentaire'        => ['nullable', 'string', 'max:255'],
        ]);

        $presence = Presence::create($validated);

        // Recharger les valeurs enregistrées en base de données.
        $presence->refresh();
        
        // Détecter les anomalies de la présence manuelle.
        app(PointageService::class)->analyserPresenceManuelle($presence);
        
        // Actualiser les données après analyse.
        $presence->refresh();

        // Enregistrer l'action dans l'historique.
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'creation',
            'module'      => 'presences',
            'description' => 'Présence créée pour l’employé ID '
                . $presence->employe_id
                . ' le '
                . \Illuminate\Support\Carbon::parse($presence->date)->format('d/m/Y')
                . ' (statut : '
                . $presence->statut
                . ').',
            'type_cible'  => 'presence',
            'cible_id'    => $presence->id,
            'nouvelle_valeur' => $presence->only([
                'id',
                'employe_id',
                'date',
                'heure_entree',
                'heure_sortie',
                'statut',
                'retard_minutes',
                'heures_travaillees',
                'commentaire',
            ]),
            'adresse_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notifier l'employé uniquement en cas d'absence ou de retard.
        if (in_array($presence->statut, ['absent', 'retard'], true)) {
            $employe = Employe::with('user')->find($presence->employe_id);

            if ($employe?->user) {
                $date = \Illuminate\Support\Carbon::parse($presence->date)->format('d/m/Y');

                $message = $presence->statut === 'absent'
                    ? 'Une absence a été enregistrée pour vous le ' . $date . '.'
                    : 'Un retard de ' . ($presence->retard_minutes ?? 0)
                        . ' minute(s) a été enregistré pour vous le ' . $date . '.';

                app(NotificationService::class)->envoyer(
                    $employe->user,
                    $presence->statut === 'absent'
                        ? 'Absence enregistrée'
                        : 'Retard enregistré',
                    $message,
                    'warning',
                    null,
                    'presence'
                );
            }
        }

        return redirect()
            ->route('gestion.presences.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    /**
     * Fiche détaillée.
     */
    public function show(string $id): View
    {
        $presence = Presence::with('employe')->findOrFail($id);

        return view('gestion.presences.show', compact('presence'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(string $id): View
    {
        $presence = Presence::findOrFail($id);
        $employes = Employe::orderBy('nom')->get();

        return view('gestion.presences.edit', compact('presence', 'employes'));
    }

    /**
     * Enregistrer les modifications.
     */
    public function update(Request $request, string $id)
    {
        $presence = Presence::findOrFail($id);
        $ancienStatut = $presence->statut;

        // Conserver les anciennes valeurs avant modification.
        $anciennesValeurs = $presence->only([
            'employe_id',
            'date',
            'heure_entree',
            'heure_sortie',
            'statut',
            'retard_minutes',
            'heures_travaillees',
            'commentaire',
        ]);

        $validated = $request->validate([
            'employe_id'         => ['required', 'exists:employes,id'],
            'date'               => ['required', 'date'],
            'heure_entree'       => ['nullable', 'date_format:H:i'],
            'heure_sortie'       => ['nullable', 'date_format:H:i', 'after:heure_entree'],
            'statut'             => ['required', 'in:present,absent,retard,conge'],
            'retard_minutes'     => ['nullable', 'integer', 'min:0'],
            'heures_travaillees' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'commentaire'        => ['nullable', 'string', 'max:255'],
        ]);

        $presence->update($validated);

        // Détecter les anomalies après modification.
        app(PointageService::class)->analyserPresenceManuelle($presence);

        // Actualiser les données après analyse.
        $presence->refresh();

        // Enregistrer la modification dans l'historique.
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'modification',
            'module'      => 'presences',
            'description' => 'Présence modifiée pour l’employé ID '
                . $presence->employe_id
                . ' le '
                . \Illuminate\Support\Carbon::parse($presence->date)->format('d/m/Y')
                . '.',
            'type_cible'  => 'presence',
            'cible_id'    => $presence->id,
            'ancienne_valeur' => $anciennesValeurs,
            'nouvelle_valeur' => $presence->only([
                'employe_id',
                'date',
                'heure_entree',
                'heure_sortie',
                'statut',
                'retard_minutes',
                'heures_travaillees',
                'commentaire',
            ]),
            'adresse_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Notifier uniquement lorsque le statut devient absent ou retard.
        if (
            $ancienStatut !== $presence->statut
            && in_array($presence->statut, ['absent', 'retard'], true)
        ) {
            $employe = Employe::with('user')->find($presence->employe_id);
        
            if ($employe?->user) {
                $date = \Illuminate\Support\Carbon::parse($presence->date)->format('d/m/Y');
        
                $message = $presence->statut === 'absent'
                    ? 'Votre présence du ' . $date . ' a été modifiée : vous êtes marqué absent.'
                    : 'Votre présence du ' . $date . ' a été modifiée : retard de '
                        . ($presence->retard_minutes ?? 0) . ' minute(s).';
        
                app(NotificationService::class)->envoyer(
                    $employe->user,
                    $presence->statut === 'absent'
                        ? 'Présence modifiée : absence'
                        : 'Présence modifiée : retard',
                    $message,
                    'warning',
                    null,
                    'presence'
                );
            }
        }

        return redirect()
            ->route('gestion.presences.index')
            ->with('success', 'Présence modifiée avec succès.');
    }
    
    /**
     * Supprimer une présence.
     */
    public function destroy(string $id)
    {
        $presence = Presence::findOrFail($id);

        // Enregistrer la suppression dans l'historique avant de supprimer.
        HistoriqueAction::create([
            'user_id'     => Auth::id(),
            'action'      => 'suppression',
            'module'      => 'presences',
            'description' => 'Présence supprimée pour l’employé ID '
                . $presence->employe_id
                . ' le '
                . \Illuminate\Support\Carbon::parse($presence->date)->format('d/m/Y')
                . '.',
            'type_cible'  => 'presence',
            'cible_id'    => $presence->id,
            'ancienne_valeur' => $presence->only([
                'id',
                'employe_id',
                'date',
                'heure_entree',
                'heure_sortie',
                'statut',
                'retard_minutes',
                'heures_travaillees',
                'commentaire',
            ]),
            'adresse_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $presence->delete();

        return redirect()
            ->route('gestion.presences.index')
            ->with('success', 'Présence supprimée.');
    }
}