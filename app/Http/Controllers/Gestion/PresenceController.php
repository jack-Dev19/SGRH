<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\View\View;

class PresenceController extends Controller
{
    public function index(): View
    {
        $presences = Presence::with('employe')
            ->orderByDesc('date')
            ->paginate(20);

        return view('gestion.presences.index', compact('presences'));
    }

    public function create(): View
    {
        return view('gestion.presences.create');
    }

    public function store()
    {
        return redirect()->route('gestion.presences.index');
    }

    public function show(string $id): View
    {
        $presence = Presence::with('employe')->findOrFail($id);
        return view('gestion.presences.show', compact('presence'));
    }

    public function edit(string $id): View
    {
        $presence = Presence::findOrFail($id);
        return view('gestion.presences.edit', compact('presence'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.presences.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.presences.index');
    }
}