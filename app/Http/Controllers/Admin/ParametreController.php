<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoriqueAction;
use App\Models\JourFerie;
use App\Models\Role;
use App\Models\TypeConge;
use App\Models\User;
use Illuminate\View\View;

/**
 * ParametreController
 * -------------------
 * Gestion des paramètres système.
 * Réservé au rôle Administrateur.
 */
class ParametreController extends Controller
{
    /**
     * Page d'accueil des paramètres : menu des sous-sections.
     */
    public function index(): View
    {
        $stats = [
            'utilisateurs' => User::count(),
            'roles'        => Role::count(),
            'types_conges' => TypeConge::count(),
            'jours_feries' => JourFerie::count(),
            'actions_log'  => HistoriqueAction::count(),
        ];

        return view('admin.parametres.index', compact('stats'));
    }

    /**
     * Liste des utilisateurs (comptes).
     */
    public function utilisateurs(): View
    {
        $utilisateurs = User::with('role')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.parametres.utilisateurs', compact('utilisateurs'));
    }

    /**
     * Liste des rôles.
     */
    public function roles(): View
    {
        $roles = Role::withCount('users')
            ->orderBy('nom')
            ->get();

        return view('admin.parametres.roles', compact('roles'));
    }

    /**
     * Liste des types de congés.
     */
    public function typesConges(): View
    {
        $types = TypeConge::orderBy('nom')->get();

        return view('admin.parametres.types-conges', compact('types'));
    }

    /**
     * Liste des jours fériés.
     */
    public function joursFeries(): View
    {
        $jours = JourFerie::orderBy('date')->get();

        return view('admin.parametres.jours-feries', compact('jours'));
    }

    /**
     * Historique des actions (audit log).
     */
    public function historique(): View
    {
        $actions = HistoriqueAction::with('user')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.parametres.historique', compact('actions'));
    }
}