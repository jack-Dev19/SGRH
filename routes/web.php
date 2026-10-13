<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// =============================================
// IMPORT DES CONTRÔLEURS DE DASHBOARD
// =============================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Rh\DashboardController as RhDashboard;
use App\Http\Controllers\Chef\DashboardController as ChefDashboard;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;


/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('index');
})->name('home');


/*
|--------------------------------------------------------------------------
| ROUTES D'AUTHENTIFICATION (invités uniquement)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Afficher le formulaire de connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Traiter la connexion
    Route::post('/login', [LoginController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (utilisateurs connectés)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // =============================================
    // ESPACE ADMINISTRATEUR
    // =============================================
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        });

    // =============================================
    // ESPACE RESPONSABLE RH
    // =============================================
    Route::middleware('role:rh')
        ->prefix('rh')
        ->name('rh.')
        ->group(function () {
            Route::get('/dashboard', [RhDashboard::class, 'index'])->name('dashboard');
        });

    // =============================================
    // ESPACE CHEF DE DÉPARTEMENT
    // =============================================
    Route::middleware('role:chef')
        ->prefix('chef')
        ->name('chef.')
        ->group(function () {
            Route::get('/dashboard', [ChefDashboard::class, 'index'])->name('dashboard');
        });

    // =============================================
    // ESPACE EMPLOYÉ
    // ⚠️ NOM DE ROUTE : employe.dashboard (sans "e" final)
    // =============================================
    Route::middleware('role:employe')
        ->prefix('employee')
        ->name('employe.')
        ->group(function () {
            Route::get('/dashboard', [EmployeeDashboard::class, 'index'])->name('dashboard');
        });
});