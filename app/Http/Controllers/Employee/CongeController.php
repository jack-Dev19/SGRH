<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\TypeConge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CongeController extends Controller
{
    private function getEmploye()
    {
        return Employe::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(): View
    {
        $employe = $this->getEmploye();

        $conges = DemandeConge::with('typeConge')
            ->where('employe_id', $employe->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total'      => DemandeConge::where('employe_id', $employe->id)->count(),
            'en_attente' => DemandeConge::where('employe_id', $employe->id)->where('statut', 'en_attente')->count(),
            'approuve'   => DemandeConge::where('employe_id', $employe->id)->where('statut', 'approuve')->count(),
            'refuse'     => DemandeConge::where('employe_id', $employe->id)->where('statut', 'refuse')->count(),
        ];

        return view('employee.conges.index', compact('conges', 'stats'));
    }

    public function create(): View
    {
        $typesConges = TypeConge::orderBy('nom')->get();
        return view('employee.conges.create', compact('typesConges'));
    }

    public function store(Request $request)
    {
        $employe = $this->getEmploye();

        $validated = $request->validate([
            'type_conge_id' => ['required', 'exists:types_conges,id'],
            'date_debut'    => ['required', 'date', 'after_or_equal:today'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'motif'         => ['nullable', 'string', 'max:255'],
        ]);

        $debut = \Carbon\Carbon::parse($validated['date_debut']);
        $fin   = \Carbon\Carbon::parse($validated['date_fin']);

        $validated['employe_id']   = $employe->id;
        $validated['nombre_jours'] = $debut->diffInDays($fin) + 1;
        $validated['statut']       = 'en_attente';

        DemandeConge::create($validated);

        return redirect()
            ->route('employe.conges.index')
            ->with('success', 'Votre demande a été envoyée pour validation.');
    }
}