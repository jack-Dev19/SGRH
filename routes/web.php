<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Chef\CongeController as ChefCongeController;
use App\Http\Controllers\Chef\DashboardController as ChefDashboardController;
use App\Http\Controllers\Chef\EquipeController as ChefEquipeController;
use App\Http\Controllers\Chef\PresenceController as ChefPresenceController;
use App\Http\Controllers\Employee\CongeController as EmployeeCongeController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\PaieController as EmployeePaieController;
use App\Http\Controllers\Employee\PresenceController as EmployeePresenceController;
use App\Http\Controllers\Employee\ProfilController as EmployeeProfilController;
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
    // CHEF DE DÉPARTEMENT — Espace
    // ==============================================================
    Route::middleware('role:Chef de département')
        ->prefix('chef')
        ->name('chef.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [ChefDashboardController::class, 'index'])
                ->name('dashboard');

            // Équipe
            Route::get('/equipe', [ChefEquipeController::class, 'index'])
                ->name('equipe.index');

            // Congés à valider
            Route::get('/conges', [ChefCongeController::class, 'index'])
                ->name('conges.index');
            Route::get('/conges/{id}', [ChefCongeController::class, 'show'])
                ->name('conges.show');
            Route::post('/conges/{id}/approuver', [ChefCongeController::class, 'approuver'])
                ->name('conges.approuver');
            Route::post('/conges/{id}/refuser', [ChefCongeController::class, 'refuser'])
                ->name('conges.refuser');

            // Présences de l'équipe
            Route::get('/presences', [ChefPresenceController::class, 'index'])
                ->name('presences.index');
        });

    // ==============================================================
    // EMPLOYÉ — Espace personnel
    // ==============================================================
    Route::middleware('role:Employé')
        ->prefix('employe')
        ->name('employe.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
                ->name('dashboard');

            // Profil
            Route::get('/profil', [EmployeeProfilController::class, 'index'])
                ->name('profil');

            // Congés
            Route::get('/conges', [EmployeeCongeController::class, 'index'])
                ->name('conges.index');
            Route::get('/conges/create', [EmployeeCongeController::class, 'create'])
                ->name('conges.create');
            Route::post('/conges', [EmployeeCongeController::class, 'store'])
                ->name('conges.store');

            // Paie
            Route::get('/paie', [EmployeePaieController::class, 'index'])
                ->name('paie.index');

            // Présences
            Route::get('/presences', [EmployeePresenceController::class, 'index'])
                ->name('presences.index');
        });

    // ==============================================================
    // RECRUTEUR
    // ==============================================================
    Route::middleware('role:Recruteur')->group(function () {
        Route::view('/recruteur/dashboard', 'recruteur.dashboard')
            ->name('recruteur.dashboard');
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

            // Présences
            Route::resource('presences', PresenceController::class);

            // Congés (CRUD + validation)
            Route::resource('conges', CongeController::class);
            Route::post('conges/{conge}/approuver', [CongeController::class, 'approuver'])
                ->name('conges.approuver');
            Route::post('conges/{conge}/refuser', [CongeController::class, 'refuser'])
                ->name('conges.refuser');

            // Salaires
            Route::resource('salaires', SalaireController::class);

            // Évaluations
            Route::resource('evaluations', EvaluationController::class);
        });
});