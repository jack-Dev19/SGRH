<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PresenceController extends Controller
{
    public function index(): View
    {
        $employe = Employe::where('user_id', Auth::id())->firstOrFail();

        $presences = Presence::where('employe_id', $employe->id)
            ->orderByDesc('date')
            ->paginate(20);

        $stats = [
            'present'    => Presence::where('employe_id', $employe->id)->where('statut', 'present')->count(),
            'absent'     => Presence::where('employe_id', $employe->id)->where('statut', 'absent')->count(),
            'retard'     => Presence::where('employe_id', $employe->id)->where('statut', 'retard')->count(),
            'heures_tot' => Presence::where('employe_id', $employe->id)->sum('heures_travaillees'),
        ];

        return view('employee.presences.index', compact('presences', 'stats'));
    }
}