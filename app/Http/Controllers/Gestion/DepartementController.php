<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\View\View;

class DepartementController extends Controller
{
    public function index(): View
    {
        $departements = Departement::withCount('employes')
            ->with('responsable')
            ->orderBy('nom')
            ->paginate(15);

        return view('gestion.departements.index', compact('departements'));
    }

    public function create(): View
    {
        return view('gestion.departements.create');
    }

    public function store()
    {
        return redirect()->route('gestion.departements.index');
    }

    public function show(string $id): View
    {
        $departement = Departement::with(['employes', 'responsable', 'postes'])
            ->findOrFail($id);

        return view('gestion.departements.show', compact('departement'));
    }

    public function edit(string $id): View
    {
        $departement = Departement::findOrFail($id);
        return view('gestion.departements.edit', compact('departement'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.departements.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.departements.index');
    }
}