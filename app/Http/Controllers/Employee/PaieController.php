<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BulletinPaie;
use App\Models\Employe;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaieController extends Controller
{
    public function index(): View
    {
        $employe = Employe::where('user_id', Auth::id())->firstOrFail();

        $bulletins = BulletinPaie::where('employe_id', $employe->id)
            ->orderByDesc('annee')
            ->orderByDesc('periode')
            ->paginate(12);

        return view('employee.paie.index', compact('bulletins'));
    }
}