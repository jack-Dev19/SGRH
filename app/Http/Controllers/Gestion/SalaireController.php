<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Salaire;
use Illuminate\View\View;

class SalaireController extends Controller
{
    public function index(): View
    {
        $salaires = Salaire::with('employe')
            ->orderByDesc('date_debut')
            ->paginate(15);

        return view('gestion.salaires.index', compact('salaires'));
    }

    public function create(): View
    {
        return view('gestion.salaires.create');
    }

    public function store()
    {
        return redirect()->route('gestion.salaires.index');
    }

    public function show(string $id): View
    {
        $salaire = Salaire::with('employe')->findOrFail($id);
        return view('gestion.salaires.show', compact('salaire'));
    }

    public function edit(string $id): View
    {
        $salaire = Salaire::findOrFail($id);
        return view('gestion.salaires.edit', compact('salaire'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.salaires.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.salaires.index');
    }
}