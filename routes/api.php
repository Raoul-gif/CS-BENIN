<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EnfantController;
use App\Http\Controllers\Api\RappelController;
use App\Http\Controllers\Api\VaccinController;
use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes API sans authentification pour le test (à enlever après)
Route::get('/vaccins', [VaccinController::class, 'index']);
Route::get('/calendrier', [VaccinController::class, 'calendrier']);

Route::middleware('auth:sanctum')->group(function () {
    // Enfants
    Route::apiResource('enfants', EnfantController::class);
    Route::get('statistiques', [EnfantController::class, 'statistiques']);
    
    // Rappels
    Route::get('enfants/{enfant}/rappels', [RappelController::class, 'index']);
    Route::post('rappels/{rappel}/effectuer', [RappelController::class, 'marquerEffectue']);
    Route::post('rappels/{rappel}/reporter', [RappelController::class, 'reporter']);
    
    // Vaccins
    Route::get('/vaccins-proteges', [VaccinController::class, 'index']);
});