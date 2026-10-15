<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CongeController (Chef)
 * ----------------------
 * Validation des congés de son équipe uniquement.
 */
class CongeController extends Controller
{
    private function getEmployeIds()
    {
        $employe = Employe::where('user_id', Auth::id())->firstOrFail();
        $departementId = $employe->departement_id;

        return Employe::where('departement_id', $departementId)->pluck('id');
    }

    public function index(Request $request): View
    {
        $employeIds = $this->getEmployeIds();

        $query = DemandeConge::with(['employe', 'typeConge', 'validateur'])
            ->whereIn('employe_id', $employeIds);

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $conges = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'      => DemandeConge::whereIn('employe_id', $employeIds)->count(),
            'en_attente' => DemandeConge::whereIn('employe_id', $employeIds)->where('statut', 'en_attente')->count(),
            'approuve'   => DemandeConge::whereIn('employe_id', $employeIds)->where('statut', 'approuve')->count(),
            'refuse'     => DemandeConge::whereIn('employe_id', $employeIds)->where('statut', 'refuse')->count(),
        ];

        return view('chef.conges.index', compact('conges', 'stats'));
    }

    public function show(string $id): View
    {
        $employeIds = $this->getEmployeIds();
        $conge = DemandeConge::with(['employe', 'typeConge', 'validateur'])
            ->whereIn('employe_id', $employeIds)
            ->findOrFail($id);

        return view('chef.conges.show', compact('conge'));
    }

    public function approuver(string $id)
    {
        $employeIds = $this->getEmployeIds();
        $conge = DemandeConge::whereIn('employe_id', $employeIds)->findOrFail($id);

        $conge->update([
            'statut'                 => 'approuve',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => 'Approuvé par le chef de département.',
        ]);

        return redirect()->back()->with('success', "Congé de {$conge->employe?->nom_complet} approuvé.");
    }

    public function refuser(Request $request, string $id)
    {
        $employeIds = $this->getEmployeIds();
        $conge = DemandeConge::whereIn('employe_id', $employeIds)->findOrFail($id);

        $request->validate([
            'commentaire_validation' => ['nullable', 'string', 'max:255'],
        ]);

        $conge->update([
            'statut'                 => 'refuse',
            'valide_par'             => Auth::id(),
            'date_validation'        => now(),
            'commentaire_validation' => $request->input('commentaire_validation') ?? 'Refusé par le chef de département.',
        ]);

        return redirect()->back()->with('success', "Congé de {$conge->employe?->nom_complet} refusé.");
    }
}