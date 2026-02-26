<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoriqueController; // 
use App\Http\Controllers\ExportController;      // 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/', function () {
//     return view('security.foot');
// });

// Routes d'authentification (si vous utilisez Breeze/Jetstream)
// ...

// Routes pour l'historique
Route::resource('enfants.historique', HistoriqueController::class)->except(['create']);


require __DIR__.'/auth.php';



// Routes pour l'export PDF
Route::prefix('export')->name('export.')->group(function () {
    Route::get('enfant/{enfant}/carnet', [ExportController::class, 'telechargerCarnet'])->name('carnet');
    Route::get('enfant/{enfant}/apercu', [ExportController::class, 'apercu'])->name('apercu');
    Route::get('carnet/{carnet}/verifier', [ExportController::class, 'verifierCarnet'])->name('verifier');
    Route::post('enfant/{enfant}/partager', [ExportController::class, 'partager'])->name('partager');
    Route::get('enfant/{enfant}/historique', [ExportController::class, 'historique'])->name('historique');
    Route::delete('carnet/{carnet}', [ExportController::class, 'supprimer'])->name('supprimer');
});

