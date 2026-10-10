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
| ROUTES PUBLIQUES (Accessibles sans être connecté)
|--------------------------------------------------------------------------
*/

// ---------------------------------------------
// Page d'accueil (Landing Page publique)
// ---------------------------------------------
Route::get('/', function () {
    return view('index'); // Retourne resources/views/index.blade.php
})->name('home');


/*
|--------------------------------------------------------------------------
| ROUTES D'AUTHENTIFICATION (Invités uniquement)
|--------------------------------------------------------------------------
| Le middleware 'guest' redirige les utilisateurs déjà connectés vers
| leur dashboard s'ils essaient d'accéder à /login.
*/

Route::middleware('guest')->group(function () {

    // Afficher le formulaire de connexion (GET /login)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Traiter la soumission du formulaire (POST /login)
    Route::post('/login', [LoginController::class, 'login']);

});


/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (Utilisateurs connectés uniquement)
|--------------------------------------------------------------------------
| Le middleware 'auth' redirige vers /login si l'utilisateur n'est pas
| authentifié.
*/

Route::middleware('auth')->group(function () {

    // ---------------------------------------------
    // Déconnexion (POST /logout)
    // ---------------------------------------------
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    /*
    |--------------------------------------------------------------------------
    | ESPACE ADMINISTRATEUR (role = admin)
    |--------------------------------------------------------------------------
    | URLs : /admin/...
    | Noms : admin.dashboard, admin.users.index, etc.
    */

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // --- Futures routes Admin (à décommenter plus tard) ---
        // Route::resource('users', UserController::class);
        // Route::resource('roles', RoleController::class);
        // Route::resource('permissions', PermissionController::class);
        // Route::get('/backups', [BackupController::class, 'index'])->name('backups');
        // Route::get('/logs', [LogController::class, 'index'])->name('logs');
        // Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE RESPONSABLE RH (role = rh)
    |--------------------------------------------------------------------------
    | URLs : /rh/...
    | Noms : rh.dashboard, rh.employees.index, etc.
    */

    Route::middleware('role:rh')->prefix('rh')->name('rh.')->group(function () {

        Route::get('/dashboard', [RhDashboard::class, 'index'])->name('dashboard');

        // --- Futures routes RH (Modules 2 à 10) ---
        // Route::resource('departments', DepartmentController::class);
        // Route::resource('posts', PostController::class);
        // Route::resource('employees', EmployeeController::class);
        // Route::resource('contracts', ContractController::class);
        // Route::resource('leaves', LeaveController::class);
        // Route::resource('attendances', AttendanceController::class);
        // Route::resource('salaries', SalaryController::class);
        // Route::resource('recruitments', RecruitmentController::class);
        // Route::resource('evaluations', EvaluationController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE CHEF DE DÉPARTEMENT (role = chef)
    |--------------------------------------------------------------------------
    | URLs : /chef/...
    | Noms : chef.dashboard, chef.team.index, etc.
    */

    Route::middleware('role:chef')->prefix('chef')->name('chef.')->group(function () {

        Route::get('/dashboard', [ChefDashboard::class, 'index'])->name('dashboard');

        // --- Futures routes Chef ---
        // Route::get('/team', [TeamController::class, 'index'])->name('team');
        // Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
        // Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        // Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
        // Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances');

    });


    /*
    |--------------------------------------------------------------------------
    | ESPACE EMPLOYÉ (role = employe)
    |--------------------------------------------------------------------------
    | URLs : /employee/...
    | Noms : employe.dashboard, employe.profile, etc.
    |
    | ⚠️ IMPORTANT : Le préfixe de nom est "employe." (français, sans "e" final)
    | pour correspondre à la valeur du rôle en base de données ("employe").
    */

    Route::middleware('role:employe')->prefix('employee')->name('employe.')->group(function () {

        Route::get('/dashboard', [EmployeeDashboard::class, 'index'])->name('dashboard');

        // --- Futures routes Employé ---
        // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
        // Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        // Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances');
        // Route::get('/payslips', [PayslipController::class, 'index'])->name('payslips');
        // Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations');

    });

});