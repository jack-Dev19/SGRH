<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Http\Request;
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

        Presence::create($validated);

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
        $presence->delete();

        return redirect()
            ->route('gestion.presences.index')
            ->with('success', 'Présence supprimée.');
    }
}