<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\ExportController;
use App\Mail\RappelVaccin;
use App\Models\Rappel;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification (si vous utilisez Breeze/Jetstream)
// ...

// Routes pour l'historique
Route::resource('enfants.historique', HistoriqueController::class)->except(['create']);

// Routes pour l'export PDF (Code de Lauriano)
Route::prefix('export')->name('export.')->group(function () {
    Route::get('enfant/{enfant}/carnet', [ExportController::class, 'telechargerCarnet'])->name('carnet');
    Route::get('enfant/{enfant}/apercu', [ExportController::class, 'apercu'])->name('apercu');
    Route::get('carnet/{carnet}/verifier', [ExportController::class, 'verifierCarnet'])->name('verifier');
    Route::post('enfant/{enfant}/partager', [ExportController::class, 'partager'])->name('partager');
    Route::get('enfant/{enfant}/historique', [ExportController::class, 'historique'])->name('historique');
    Route::delete('carnet/{carnet}', [ExportController::class, 'supprimer'])->name('supprimer');
});

// Route de preview email (Ton code)
Route::get('/preview-email', function() {
    // Récupère le premier rappel ou crée un faux pour la prévisualisation
    $rappel = Rappel::with(['enfant.user', 'vaccin'])->first();
    
    if (!$rappel) {
        // Crée un faux rappel pour la preview
        $enfant = App\Models\Enfant::first();
        $vaccin = App\Models\Vaccin::first();
        
        if (!$enfant || !$vaccin) {
            return "Crée d'abord un enfant et un vaccin !";
        }
        
        $rappel = new App\Models\Rappel();
        $rappel->enfant = $enfant;
        $rappel->vaccin = $vaccin;
    }
    
    return new App\Mail\RappelVaccin($rappel);
});