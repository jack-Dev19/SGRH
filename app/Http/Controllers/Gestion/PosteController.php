<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Poste;
use Illuminate\View\View;

class PosteController extends Controller
{
    public function index(): View
    {
        $postes = Poste::with('departement')
            ->withCount('employes')
            ->orderBy('nom')
            ->paginate(15);

        return view('gestion.postes.index', compact('postes'));
    }

    public function create(): View
    {
        return view('gestion.postes.create');
    }

    public function store()
    {
        return redirect()->route('gestion.postes.index');
    }

    public function show(string $id): View
    {
        $poste = Poste::with(['departement', 'employes'])
            ->findOrFail($id);

        return view('gestion.postes.show', compact('poste'));
    }

    public function edit(string $id): View
    {
        $poste = Poste::findOrFail($id);
        return view('gestion.postes.edit', compact('poste'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.postes.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.postes.index');
    }
}