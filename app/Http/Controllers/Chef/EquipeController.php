<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EquipeController extends Controller
{
    public function index(): View
    {
        $employe = Employe::where('user_id', Auth::id())
            ->with('departement')
            ->firstOrFail();

        $departement = $employe->departement;

        if (!$departement) {
            return view('chef.equipe.index', [
                'departement' => null,
                'membres'     => collect(),
            ]);
        }

        // Tous les membres de l'équipe sauf le chef lui-même
        $membres = Employe::with(['poste'])
            ->where('departement_id', $departement->id)
            ->where('id', '!=', $employe->id)
            ->orderBy('nom')
            ->paginate(12);

        return view('chef.equipe.index', compact('departement', 'membres'));
    }
}