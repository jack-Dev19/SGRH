<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PresenceController extends Controller
{
    public function index(Request $request): View
    {
        $employe = Employe::where('user_id', Auth::id())->firstOrFail();
        $departementId = $employe->departement_id;

        $employeIds = Employe::where('departement_id', $departementId)->pluck('id');

        $query = Presence::with('employe')->whereIn('employe_id', $employeIds);

        // Filtre date
        if ($date = $request->input('date')) {
            $query->whereDate('date', $date);
        } else {
            $query->whereDate('date', now()->toDateString());
        }

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $presences = $query->orderByDesc('date')->paginate(20)->withQueryString();

        $today = now()->toDateString();
        $stats = [
            'presents' => Presence::whereIn('employe_id', $employeIds)->whereDate('date', $today)->where('statut', 'present')->count(),
            'absents'  => Presence::whereIn('employe_id', $employeIds)->whereDate('date', $today)->where('statut', 'absent')->count(),
            'retards'  => Presence::whereIn('employe_id', $employeIds)->whereDate('date', $today)->where('statut', 'retard')->count(),
            'equipe'   => $employeIds->count(),
        ];

        return view('chef.presences.index', compact('presences', 'stats'));
    }
}