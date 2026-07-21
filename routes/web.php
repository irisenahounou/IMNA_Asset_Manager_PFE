<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\PreventiveController;

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
Route::get('/dashboard', function (){
    return view('dashboards.employe');
}) ->middleware('auth')->name('dashboard');

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

