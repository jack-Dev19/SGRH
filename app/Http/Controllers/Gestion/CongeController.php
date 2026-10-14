<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\DemandeConge;
use Illuminate\View\View;

class CongeController extends Controller
{
    public function index(): View
    {
        $conges = DemandeConge::with(['employe', 'typeConge'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('gestion.conges.index', compact('conges'));
    }

    public function create(): View
    {
        return view('gestion.conges.create');
    }

    public function store()
    {
        return redirect()->route('gestion.conges.index');
    }

    public function show(string $id): View
    {
        $conge = DemandeConge::with(['employe', 'typeConge', 'valideur'])
            ->findOrFail($id);

        return view('gestion.conges.show', compact('conge'));
    }

    public function edit(string $id): View
    {
        $conge = DemandeConge::findOrFail($id);
        return view('gestion.conges.edit', compact('conge'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.conges.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.conges.index');
    }
}