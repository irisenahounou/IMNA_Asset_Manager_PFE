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
use App\Console\Commands\AnalyserUsureMateriel;

use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
Route::post('/2fa/renvoyer', [TwoFactorController::class, 'resend'])->name('2fa.resend');

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/responsable/dashboard', function (){
   return view('dashboards.responsable');
}) ->middleware('auth')->name('responsable.dashboard');
Route::get('/technicien/dashboard', function (){
    return view('dashboards.technicien');
}) ->middleware('auth')->name('technicien.dashboard');


Route::middleware('auth')->group(function (){
    Route::get('/equipements', [EquipementController::class, 'index'])->name('equipements.index');
    Route::get('/equipements/ajouter', [EquipementController::class, 'create'])->name('equipements.create');
    Route::post('/equipements', [EquipementController::class, 'store'])->name('equipements.store');
});

Route::resource('pannes', PanneController::class);
Route::patch('pannes/{id}/statut', [PanneController::class, 'updateStatut'])->name('pannes.updateStatut');

Route::get('/reparations', [ReparationController::class, 'index'])->name('reparations.index');
Route::get('/reparations/create/{id_panne}', [ReparationController::class, 'create'])->name('reparations.create');
Route::post('/reparations', [ReparationController::class, 'store'])->name('reparations.store');

Route::get('/preventives', [PreventiveController::class, 'index'])->name('preventives.index');
Route::get('/preventives/create', [PreventiveController::class, 'create'])->name('preventives.create');
Route::post('/preventives', [PreventiveController::class, 'store'])->name('preventives.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/equipements/{id}/edit', [EquipementController::class, 'edit'])->name('equipements.edit');
Route::put('/equipements/{id}', [EquipementController::class, 'update'])->name('equipements.update');
Route::delete('/equipements/{id}', [EquipementController::class, 'destroy'])->name('equipements.destroy');

// Routes pour la gestion des composants et du stock
Route::middleware(['auth'])->group(function () {
    Route::get('/composants', [ComposantController::class, 'index'])->name('composants.index');
    Route::get('/composants/create', [ComposantController::class, 'create'])->name('composants.create');
    Route::post('/composants', [ComposantController::class, 'store'])->name('composants.store');

    // Mouvements de stock
    Route::post('/mouvements-stock', [ComposantController::class, 'stockMouvement'])->name('mouvements.store');
    Route::patch('/mouvements-stock/{id}/valider', [ComposantController::class, 'validerMouvement'])->name('mouvements.valider');

    // Traçabilité unitaire par numéro de série (RM-07)
    Route::get('/composants/{idComposant}/unites', [\App\Http\Controllers\UniteComposantController::class, 'index'])->name('unites.index');
    Route::post('/composants/{idComposant}/unites', [\App\Http\Controllers\UniteComposantController::class, 'store'])->name('unites.store');
    Route::post('/unites/{id}/affecter', [\App\Http\Controllers\UniteComposantController::class, 'affecter'])->name('unites.affecter');
    Route::post('/unites/{id}/retirer', [\App\Http\Controllers\UniteComposantController::class, 'retirer'])->name('unites.retirer');
});


Route::get('/responsable/audits', [AuditController::class, 'index'])->middleware('auth')->name('responsable.audits');

Artisan::command('imna:analyser-usure', function () {
    $this->call(AnalyserUsureMateriel::class);
});



Route::get('/dashboard/usure', [EquipementController::class, 'usureIndex'])->middleware('auth')->name('dashboard.usure');
Route::post('/dashboard/usure/analyser', function () {
    Artisan::call('imna:analyser-usure');
    return redirect()->route('dashboard.usure')->with('success', 'Analyse Python exécutée avec succès !');
})->name('analyser.usure.trigger');

Route::middleware('auth')->group(function () {
    Route::post('/notifications/{id}/lire', [\App\Http\Controllers\NotificationController::class, 'marquerLue'])->name('notifications.lire');
    Route::post('/notifications/lire-tout', [\App\Http\Controllers\NotificationController::class, 'marquerToutLu'])->name('notifications.lireTout');
    Route::get('/utilisateurs', [\App\Http\Controllers\UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/{id}', [\App\Http\Controllers\UtilisateurController::class, 'show'])->name('utilisateurs.show');
    Route::delete('/utilisateurs/{id}/tokens/{tokenId}', [\App\Http\Controllers\UtilisateurController::class, 'revoquerToken'])->name('utilisateurs.revoquerToken');
    Route::delete('/utilisateurs/{id}/tokens', [\App\Http\Controllers\UtilisateurController::class, 'revoquerTousLesTokens'])->name('utilisateurs.revoquerTousLesTokens');
});

