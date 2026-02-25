<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\ExportController;
use App\Mail\RappelVaccin;
use App\Models\Rappel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Routes Utilisateur (dashboard et profil)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes Administrateur
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/enfants', [AdminController::class, 'enfants'])->name('enfants');
    Route::get('/enfants/{id}', [AdminController::class, 'showEnfant'])->name('enfants.show');
    Route::delete('/enfants/{id}', [AdminController::class, 'deleteEnfant'])->name('enfants.delete');
    Route::get('/vaccins', [AdminController::class, 'vaccins'])->name('vaccins');
    Route::get('/vaccins/{id}/edit', [AdminController::class, 'editVaccin'])->name('vaccins.edit');
    Route::put('/vaccins/{id}', [AdminController::class, 'updateVaccin'])->name('vaccins.update');
    Route::delete('/vaccins/{id}', [AdminController::class, 'deleteVaccin'])->name('vaccins.delete');
    Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');
    Route::get('/parametres', [AdminController::class, 'settings'])->name('settings');
    Route::post('/parametres', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/admin-management', [AdminController::class, 'adminManagement'])->name('management');
    Route::post('/admin-management/add', [AdminController::class, 'addAdmin'])->name('add');
    Route::delete('/admin-management/{id}', [AdminController::class, 'removeAdmin'])->name('remove');
});

/*
|--------------------------------------------------------------------------
| Routes pour l'historique et export
|--------------------------------------------------------------------------
*/
Route::resource('enfants.historique', HistoriqueController::class)->except(['create']);

Route::prefix('export')->name('export.')->group(function () {
    Route::get('enfant/{enfant}/carnet', [ExportController::class, 'telechargerCarnet'])->name('carnet');
    Route::get('enfant/{enfant}/apercu', [ExportController::class, 'apercu'])->name('apercu');
    Route::get('carnet/{carnet}/verifier', [ExportController::class, 'verifierCarnet'])->name('verifier');
    Route::post('enfant/{enfant}/partager', [ExportController::class, 'partager'])->name('partager');
    Route::get('enfant/{enfant}/historique', [ExportController::class, 'historique'])->name('historique');
    Route::delete('carnet/{carnet}', [ExportController::class, 'supprimer'])->name('supprimer');
});

/*
|--------------------------------------------------------------------------
| Route de preview email (Samuel)
|--------------------------------------------------------------------------
*/
Route::get('/preview-email', function() {
    $rappel = Rappel::with(['enfant.user', 'vaccin'])->first();
    
    if (!$rappel) {
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

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';