<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComposantController;
use App\Http\Controllers\Api\MaterielController;
use App\Http\Controllers\Api\PanneController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Application Mobile React Native)
|--------------------------------------------------------------------------
| Authentification par Token Sanctum (RM-04), consommée exclusivement
| par le mobile. L'interface Web continue d'utiliser Session/Cookie.
*/

// --- Authentification (publiques) ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/2fa/verify', [AuthController::class, 'verify2fa']);
Route::post('/2fa/resend', [AuthController::class, 'resend2fa']);

// --- Routes protégées par token (middleware auth:sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Module Parc (Assets) - consultation terrain
    Route::get('/materiels', [MaterielController::class, 'index']);
    Route::get('/materiels/{id}', [MaterielController::class, 'show']);

    // Module Maintenance / Cycle des tickets
    Route::get('/pannes', [PanneController::class, 'index']);
    Route::post('/pannes', [PanneController::class, 'store']);
    Route::get('/pannes/{id}', [PanneController::class, 'show']);
    Route::post('/pannes/{id}/prendre-en-charge', [PanneController::class, 'prendreEnCharge']);
    Route::post('/pannes/{id}/cloturer', [PanneController::class, 'cloturer']);

    // Module Stock - demande uniquement (validation réservée au DSI sur le Web)
    Route::get('/composants', [ComposantController::class, 'index']);
    Route::post('/mouvements-stock', [ComposantController::class, 'demander']);
});