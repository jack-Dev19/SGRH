<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Gestion\CongeController;
use App\Http\Controllers\Gestion\ContratController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\EmployeController;
use App\Http\Controllers\Gestion\EvaluationController;
use App\Http\Controllers\Gestion\PosteController;
use App\Http\Controllers\Gestion\PresenceController;
use App\Http\Controllers\Gestion\SalaireController;
use App\Http\Controllers\Rh\DashboardController as RhDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web — Digital Agency SGRH
|--------------------------------------------------------------------------
*/

// ==================================================================
// PAGE PUBLIQUE
// ==================================================================
Route::get('/', function () {
    return view('index');
})->name('home');

// ==================================================================
// AUTHENTIFICATION (invités uniquement)
// ==================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ==================================================================
// ROUTES PROTÉGÉES (utilisateurs connectés)
// ==================================================================
Route::middleware('auth')->group(function () {

    // --------------------------------------------------------------
    // Déconnexion
    // --------------------------------------------------------------
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // --------------------------------------------------------------
    // Redirection intelligente après login
    // --------------------------------------------------------------
    Route::get('/dashboard', function () {
        $roleMapping = [
            'Administrateur'      => 'admin.dashboard',
            'Responsable RH'      => 'rh.dashboard',
            'Chef de département' => 'chef.dashboard',
            'Employé'             => 'employe.dashboard',
            'Recruteur'           => 'recruteur.dashboard',
        ];

        $target = $roleMapping[auth()->user()->role->nom ?? ''] ?? null;

        return $target
            ? redirect()->route($target)
            : 'Aucun rôle assigné à votre compte.';
    })->name('dashboard');

    // ==============================================================
    // ADMIN — Dashboard
    // ==============================================================
    Route::middleware('role:Administrateur')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');
    });

    // ==============================================================
    // RH — Dashboard
    // ==============================================================
    Route::middleware('role:Responsable RH')->group(function () {
        Route::get('/rh/dashboard', [RhDashboardController::class, 'index'])
            ->name('rh.dashboard');
    });

    // ==============================================================
    // CHEF DE DÉPARTEMENT
    // ==============================================================
    Route::middleware('role:Chef de département')->group(function () {
        Route::view('/chef/dashboard', 'chef.dashboard')->name('chef.dashboard');
    });

    // ==============================================================
    // EMPLOYÉ
    // ==============================================================
    Route::middleware('role:Employé')->group(function () {
        Route::view('/employe/dashboard', 'employee.dashboard')->name('employe.dashboard');
    });

    // ==============================================================
    // RECRUTEUR
    // ==============================================================
    Route::middleware('role:Recruteur')->group(function () {
        Route::view('/recruteur/dashboard', 'recruteur.dashboard')->name('recruteur.dashboard');
    });

    // ==============================================================
    // GESTION — PARTAGÉ Admin + RH
    // ==============================================================
    Route::middleware('role:Administrateur,Responsable RH')
        ->prefix('gestion')
        ->name('gestion.')
        ->group(function () {

            // Employés
            Route::resource('employes', EmployeController::class);

            // Départements
            Route::resource('departements', DepartementController::class);

            // Postes
            Route::resource('postes', PosteController::class);

            // Contrats
            Route::resource('contrats', ContratController::class);

            // Congés
            Route::resource('conges', CongeController::class);

            // Présences
            Route::resource('presences', PresenceController::class);

            // Salaires
            Route::resource('salaires', SalaireController::class);

            // Évaluations
            Route::resource('evaluations', EvaluationController::class);
        });

    // ==============================================================
    // PARAMÈTRES — Admin UNIQUEMENT
    // ==============================================================
    Route::middleware('role:Administrateur')
        ->prefix('admin/parametres')
        ->name('admin.parametres.')
        ->group(function () {

            Route::get('/', [ParametreController::class, 'index'])
                ->name('index');

            Route::get('/utilisateurs', [ParametreController::class, 'utilisateurs'])
                ->name('utilisateurs');

            Route::get('/roles', [ParametreController::class, 'roles'])
                ->name('roles');

            Route::get('/types-conges', [ParametreController::class, 'typesConges'])
                ->name('types-conges');

            Route::get('/jours-feries', [ParametreController::class, 'joursFeries'])
                ->name('jours-feries');

            Route::get('/historique', [ParametreController::class, 'historique'])
                ->name('historique');
        });
});