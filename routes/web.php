<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\PreventiveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComposantController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\TechnicienDashboardController;
use App\Http\Controllers\EmployeDashboardController;
use App\Console\Commands\AnalyserUsureMateriel;

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Routes publiques (pas de session requise)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
Route::post('/2fa/renvoyer', [TwoFactorController::class, 'resend'])->name('2fa.resend');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Espace TECHNICIEN (Web) — équivalent des écrans mobile US-01 à US-04
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:technicien'])->group(function () {
    Route::get('/technicien/dashboard', [TechnicienDashboardController::class, 'index'])->name('technicien.dashboard');
    Route::post('/technicien/pannes/{id}/prendre-en-charge', [TechnicienDashboardController::class, 'prendreEnCharge'])->name('technicien.prendreEnCharge');
    Route::post('/technicien/pannes/{id}/cloturer', [TechnicienDashboardController::class, 'cloturer'])->name('technicien.cloturer');
});

/*
|--------------------------------------------------------------------------
| Espace EMPLOYÉ (Web) — équivalent des écrans mobile US-01/US-02
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:employe'])->group(function () {
    Route::get('/employe/dashboard', [EmployeDashboardController::class, 'index'])->name('employe.dashboard');
    Route::post('/employe/pannes', [EmployeDashboardController::class, 'declarer'])->name('employe.declarer');
});

/*
|--------------------------------------------------------------------------
| Espace RESPONSABLE / DSI (Web) — supervision globale
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:responsable'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Parc matériel
    Route::get('/equipements', [EquipementController::class, 'index'])->name('equipements.index');
    Route::get('/equipements/ajouter', [EquipementController::class, 'create'])->name('equipements.create');
    Route::post('/equipements', [EquipementController::class, 'store'])->name('equipements.store');
    Route::get('/equipements/{id}/edit', [EquipementController::class, 'edit'])->name('equipements.edit');
    Route::put('/equipements/{id}', [EquipementController::class, 'update'])->name('equipements.update');
    Route::delete('/equipements/{id}', [EquipementController::class, 'destroy'])->name('equipements.destroy');

    // Pannes (vue globale DSI, distincte du dashboard technicien)
    Route::resource('pannes', PanneController::class);
    Route::patch('pannes/{id}/statut', [PanneController::class, 'updateStatut'])->name('pannes.updateStatut');

    // Réparations (planification manuelle par le DSI)
    Route::get('/reparations', [ReparationController::class, 'index'])->name('reparations.index');
    Route::get('/reparations/create/{id_panne}', [ReparationController::class, 'create'])->name('reparations.create');
    Route::post('/reparations', [ReparationController::class, 'store'])->name('reparations.store');

    // Maintenance préventive
    Route::get('/preventives', [PreventiveController::class, 'index'])->name('preventives.index');
    Route::get('/preventives/create', [PreventiveController::class, 'create'])->name('preventives.create');
    Route::post('/preventives', [PreventiveController::class, 'store'])->name('preventives.store');

    // Stock et composants
    Route::get('/composants', [ComposantController::class, 'index'])->name('composants.index');
    Route::get('/composants/create', [ComposantController::class, 'create'])->name('composants.create');
    Route::post('/composants', [ComposantController::class, 'store'])->name('composants.store');
    Route::post('/mouvements-stock', [ComposantController::class, 'stockMouvement'])->name('mouvements.store');
    Route::patch('/mouvements-stock/{id}/valider', [ComposantController::class, 'validerMouvement'])->name('mouvements.valider');

    // Traçabilité unitaire par numéro de série (RM-07)
    Route::get('/composants/{idComposant}/unites', [\App\Http\Controllers\UniteComposantController::class, 'index'])->name('unites.index');
    Route::post('/composants/{idComposant}/unites', [\App\Http\Controllers\UniteComposantController::class, 'store'])->name('unites.store');
    Route::post('/unites/{id}/affecter', [\App\Http\Controllers\UniteComposantController::class, 'affecter'])->name('unites.affecter');
    Route::post('/unites/{id}/retirer', [\App\Http\Controllers\UniteComposantController::class, 'retirer'])->name('unites.retirer');

    // Journal d'audit
    Route::get('/responsable/audits', [AuditController::class, 'index'])->name('responsable.audits');

    // Analyse prédictive d'usure
    Route::get('/dashboard/usure', [EquipementController::class, 'usureIndex'])->name('dashboard.usure');
    Route::post('/dashboard/usure/analyser', function () {
        Artisan::call('imna:analyser-usure');
        return redirect()->route('dashboard.usure')->with('success', 'Analyse Python exécutée avec succès !');
    })->name('analyser.usure.trigger');

    // Gestion des accès mobiles (RM-04)
    Route::get('/utilisateurs', [\App\Http\Controllers\UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/{id}', [\App\Http\Controllers\UtilisateurController::class, 'show'])->name('utilisateurs.show');
    Route::delete('/utilisateurs/{id}/tokens/{tokenId}', [\App\Http\Controllers\UtilisateurController::class, 'revoquerToken'])->name('utilisateurs.revoquerToken');
    Route::delete('/utilisateurs/{id}/tokens', [\App\Http\Controllers\UtilisateurController::class, 'revoquerTousLesTokens'])->name('utilisateurs.revoquerTousLesTokens');
});

/*
|--------------------------------------------------------------------------
| Notifications — accessibles à tout utilisateur connecté, quel que soit son rôle
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{id}/lire', [\App\Http\Controllers\NotificationController::class, 'marquerLue'])->name('notifications.lire');
    Route::post('/notifications/lire-tout', [\App\Http\Controllers\NotificationController::class, 'marquerToutLu'])->name('notifications.lireTout');
});

Artisan::command('imna:analyser-usure', function () {
    $this->call(AnalyserUsureMateriel::class);
});