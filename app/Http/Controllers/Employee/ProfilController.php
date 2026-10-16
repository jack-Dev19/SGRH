<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(): View
    {
        $employe = Employe::where('user_id', Auth::id())
            ->with(['departement', 'poste', 'contrats', 'competences'])
            ->firstOrFail();

        return view('employee.profil', compact('employe'));
    }
}