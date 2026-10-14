<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(): View
    {
        $evaluations = Evaluation::with('employe')
            ->orderByDesc('date_evaluation')
            ->paginate(15);

        return view('gestion.evaluations.index', compact('evaluations'));
    }

    public function create(): View
    {
        return view('gestion.evaluations.create');
    }

    public function store()
    {
        return redirect()->route('gestion.evaluations.index');
    }

    public function show(string $id): View
    {
        $evaluation = Evaluation::with('employe')->findOrFail($id);
        return view('gestion.evaluations.show', compact('evaluation'));
    }

    public function edit(string $id): View
    {
        $evaluation = Evaluation::findOrFail($id);
        return view('gestion.evaluations.edit', compact('evaluation'));
    }

    public function update(string $id)
    {
        return redirect()->route('gestion.evaluations.index');
    }

    public function destroy(string $id)
    {
        return redirect()->route('gestion.evaluations.index');
    }
}