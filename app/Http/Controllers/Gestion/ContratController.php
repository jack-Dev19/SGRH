<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Contrat;
use Illuminate\View\View;

class ContratController extends Controller
{
    public function index(): View
    {
        $contrats = Contrat::with('employe')
            ->orderByDesc('date_debut')
            ->paginate(15);

        return view('gestion.contrats.index', compact('contrats'));
    }

    public function create(): View
    {
        return view('gestion.contrats.create');
    }

    public function store()
    {
        return redirect()->route('gestion.contrats.index');
    }

    public function show(string $id): View
    {
        $contrat = Contrat::with('employe')->findOrFail($id);
        return view('gestion.contrats.show', compact('contrat'));
    }

    public function edit(string $id): View
    {
        $contrat = Contrat::findOrFail($id);
        return view('gestion.contrats.edit', compact('contrat'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.contrats.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.contrats.index');
    }
}