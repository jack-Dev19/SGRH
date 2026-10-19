<?php

/*
|=============================================================================
| ROUTES WEB — Digital Agency SGRH
|=============================================================================
*/

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
use App\Http\Controllers\Gestion\AbsenceController;
use App\Http\Controllers\Gestion\CongeController;
use App\Http\Controllers\Gestion\ContratController;
use App\Http\Controllers\Gestion\DepartementController;
use App\Http\Controllers\Gestion\DocumentController;
use App\Http\Controllers\Gestion\EmployeController;
use App\Http\Controllers\Gestion\EvaluationController;
use App\Http\Controllers\Gestion\OffreEmploiController;
use App\Http\Controllers\Gestion\PointageController;
use App\Http\Controllers\Gestion\PosteController;
use App\Http\Controllers\Gestion\PresenceController;
use App\Http\Controllers\Gestion\QrCodeController;
use App\Http\Controllers\Gestion\SalaireController;
use App\Http\Controllers\Gestion\UserController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Rh\DashboardController as RhDashboardController;
use App\Services\AbsenceService;
use Illuminate\Support\Facades\Route;

// ==================================================================
// PAGES PUBLIQUES
// ==================================================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/offres', [PublicController::class, 'toutesLesOffres'])->name('offres.index');
Route::get('/offre/{slug}', [PublicController::class, 'detailOffre'])->name('offres.show');

// ==================================================================
// AUTHENTIFICATION
// ==================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ==================================================================
// ROUTES PROTÉGÉES
// ==================================================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Redirection intelligente après login
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

    // ADMIN
    Route::middleware('role:Administrateur')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // RH
    Route::middleware('role:Responsable RH')->group(function () {
        Route::get('/rh/dashboard', [RhDashboardController::class, 'index'])->name('rh.dashboard');
    });

    // CHEF DE DÉPARTEMENT
    Route::middleware('role:Chef de département')
        ->prefix('chef')
        ->name('chef.')
        ->group(function () {
            Route::get('/dashboard', [ChefDashboardController::class, 'index'])->name('dashboard');
            Route::get('/equipe', [ChefEquipeController::class, 'index'])->name('equipe.index');
            Route::get('/conges', [ChefCongeController::class, 'index'])->name('conges.index');
            Route::get('/conges/{id}', [ChefCongeController::class, 'show'])->name('conges.show');
            Route::post('/conges/{id}/approuver', [ChefCongeController::class, 'approuver'])->name('conges.approuver');
            Route::post('/conges/{id}/refuser', [ChefCongeController::class, 'refuser'])->name('conges.refuser');
            Route::get('/presences', [ChefPresenceController::class, 'index'])->name('presences.index');
        });

    // EMPLOYÉ
    Route::middleware('role:Employé')
        ->prefix('employe')
        ->name('employe.')
        ->group(function () {
            Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
            Route::get('/profil', [EmployeeProfilController::class, 'index'])->name('profil');
            Route::get('/conges', [EmployeeCongeController::class, 'index'])->name('conges.index');
            Route::get('/conges/create', [EmployeeCongeController::class, 'create'])->name('conges.create');
            Route::post('/conges', [EmployeeCongeController::class, 'store'])->name('conges.store');
            Route::get('/paie', [EmployeePaieController::class, 'index'])->name('paie.index');
            Route::get('/presences', [EmployeePresenceController::class, 'index'])->name('presences.index');
        });

    // RECRUTEUR
    Route::middleware('role:Recruteur')->group(function () {
        Route::view('/recruteur/dashboard', 'recruteur.dashboard')->name('recruteur.dashboard');
    });

    // ==============================================================
    // GESTION — Admin + RH
    // ==============================================================
    Route::middleware('role:Administrateur,Responsable RH')
        ->prefix('gestion')
        ->name('gestion.')
        ->group(function () {

            // ---------- Employés ----------
            Route::resource('employes', EmployeController::class);

            Route::delete('employes/{employe}/supprimer-tout',
                [EmployeController::class, 'supprimerTout'])
                ->name('employes.supprimer-tout');

            Route::delete('employes/{employe}/pointages',
                [PointageController::class, 'supprimerParEmploye'])
                ->name('employes.pointages.supprimer');

            // ---------- Utilisateurs ----------
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/',              [UserController::class, 'index'])->name('index');
                Route::get('/create',        [UserController::class, 'create'])->name('create');
                Route::post('/',             [UserController::class, 'store'])->name('store');
                Route::get('/{user}',        [UserController::class, 'show'])->name('show');
                Route::get('/{user}/edit',   [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}',        [UserController::class, 'update'])->name('update');
                Route::delete('/{user}',     [UserController::class, 'destroy'])->name('destroy');
            });

            // ---------- QR Codes ----------
            Route::get ('employes/{employe}/qr',           [QrCodeController::class, 'show'])->name('employes.qr.show');
            Route::post('employes/{employe}/qr/generer',   [QrCodeController::class, 'generer'])->name('employes.qr.generer');
            Route::post('employes/{employe}/qr/revoquer',  [QrCodeController::class, 'revoquer'])->name('employes.qr.revoquer');
            Route::get ('employes/{employe}/qr/image',     [QrCodeController::class, 'image'])->name('employes.qr.image');
            Route::get ('employes/{employe}/qr/print',     [QrCodeController::class, 'imprimer'])->name('employes.qr.print');
            Route::get ('employes/{employe}/qr/download',  [QrCodeController::class, 'downloadQr'])->name('employes.qr.download');

            // ---------- Départements / Postes ----------
            Route::resource('departements', DepartementController::class);
            Route::resource('postes', PosteController::class);

            // ⭐ API : postes par département (pour le formulaire employé)
            Route::get('api/postes/par-departement/{departementId}',
                [PosteController::class, 'parDepartement'])
                ->name('postes.par-departement');

            // ---------- Contrats ----------
            Route::get('contrats/{id}/download',   [ContratController::class, 'downloadPdf'])->name('contrats.download');
            Route::get('contrats/{id}/fiche-paie', [ContratController::class, 'fichePaie'])->name('contrats.fiche-paie');
            Route::resource('contrats', ContratController::class);

            // ---------- Documents ----------
            Route::get('employes/{employe}/documents',
                [DocumentController::class, 'index'])->name('employes.documents.index');
            Route::get('employes/{employe}/documents/create',
                [DocumentController::class, 'create'])->name('employes.documents.create');

            Route::get   ('documents',                     [DocumentController::class, 'index'])->name('documents.index');
            Route::get   ('documents/create',              [DocumentController::class, 'create'])->name('documents.create');
            Route::post  ('documents',                     [DocumentController::class, 'store'])->name('documents.store');
            Route::get   ('documents/{document}',          [DocumentController::class, 'show'])->name('documents.show');
            Route::get   ('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
            Route::get   ('documents/{document}/preview',  [DocumentController::class, 'preview'])->name('documents.preview');
            Route::get   ('documents/{document}/edit',     [DocumentController::class, 'edit'])->name('documents.edit');
            Route::put   ('documents/{document}',          [DocumentController::class, 'update'])->name('documents.update');
            Route::delete('documents/{document}',          [DocumentController::class, 'destroy'])->name('documents.destroy');

            // ---------- Offres d'emploi ----------
            Route::prefix('offres')->name('offres.')->group(function () {
                Route::get('/',                  [OffreEmploiController::class, 'index'])->name('index');
                Route::get('/create',            [OffreEmploiController::class, 'create'])->name('create');
                Route::post('/',                 [OffreEmploiController::class, 'store'])->name('store');
                Route::get('/{offre}',           [OffreEmploiController::class, 'show'])->name('show');
                Route::get('/{offre}/edit',      [OffreEmploiController::class, 'edit'])->name('edit');
                Route::put('/{offre}',           [OffreEmploiController::class, 'update'])->name('update');
                Route::delete('/{offre}',        [OffreEmploiController::class, 'destroy'])->name('destroy');
                Route::post('/{offre}/publier',  [OffreEmploiController::class, 'publier'])->name('publier');
                Route::post('/{offre}/archiver', [OffreEmploiController::class, 'archiver'])->name('archiver');
            });

            // ---------- Présences / Congés ----------
            Route::resource('presences', PresenceController::class);
            Route::resource('conges', CongeController::class);
            Route::post('conges/{conge}/approuver', [CongeController::class, 'approuver'])->name('conges.approuver');
            Route::post('conges/{conge}/refuser',   [CongeController::class, 'refuser'])->name('conges.refuser');

            // ---------- Salaires / Évaluations ----------
            Route::resource('salaires', SalaireController::class);
            Route::resource('evaluations', EvaluationController::class);

            // ---------- Pointage QR ----------
            Route::get ('pointage/scanner',     [PointageController::class, 'scanner'])->name('pointage.scanner');
            Route::post('pointage/enregistrer', [PointageController::class, 'enregistrer'])->name('pointage.enregistrer');
            Route::get ('pointage/liste',       [PointageController::class, 'liste'])->name('pointage.liste');
            Route::get ('pointage/historique',    [PointageController::class, 'historique'])->name('pointage.historique');
            Route::get ('pointage/anomalies',     [PointageController::class, 'anomalies'])->name('pointage.anomalies');
            Route::get ('pointage/{id}/corriger', [PointageController::class, 'corriger'])->name('pointage.corriger');
            Route::put ('pointage/{id}/corriger', [PointageController::class, 'updateCorrection'])->name('pointage.updateCorrection');
            Route::delete('pointage/{id}',        [PointageController::class, 'supprimerPointage'])->name('pointage.supprimer');

            // ---------- Absences ----------
            Route::post('absences/detecter', function (AbsenceService $service) {
                $resultat = $service->detecterAbsences();
                return redirect()
                    ->back()
                    ->with('success', "Détection lancée : {$resultat['crees']} créée(s), {$resultat['ignores']} ignorée(s).");
            })->name('absences.detecter');

            Route::resource('absences', AbsenceController::class);
            Route::post('absences/{absence}/approuver', [AbsenceController::class, 'approuver'])->name('absences.approuver');
            Route::post('absences/{absence}/refuser',   [AbsenceController::class, 'refuser'])->name('absences.refuser');
        });
});