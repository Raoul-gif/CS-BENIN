<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Middleware\AdminMiddleware;
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
| Routes Administrateur (protégées par middleware admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    // Dashboard admin - nom changé de 'dashboard' à 'home' pour éviter le conflit
    Route::get('/', [AdminController::class, 'index'])->name('home');
    
    // Gestion des utilisateurs
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Gestion des enfants
    Route::get('/enfants', [AdminController::class, 'enfants'])->name('enfants');
    Route::get('/enfants/{id}', [AdminController::class, 'showEnfant'])->name('enfants.show');
    Route::delete('/enfants/{id}', [AdminController::class, 'deleteEnfant'])->name('enfants.delete');
    
    // Gestion des vaccins
    Route::get('/vaccins', [AdminController::class, 'vaccins'])->name('vaccins');
    Route::get('/vaccins/{id}/edit', [AdminController::class, 'editVaccin'])->name('vaccins.edit');
    Route::put('/vaccins/{id}', [AdminController::class, 'updateVaccin'])->name('vaccins.update');
    Route::delete('/vaccins/{id}', [AdminController::class, 'deleteVaccin'])->name('vaccins.delete');
    
    // Statistiques
    Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');
    
    // Paramètres
    Route::get('/parametres', [AdminController::class, 'settings'])->name('settings');
    Route::post('/parametres', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Gestion des administrateurs (NOUVEAU)
    Route::get('/admin-management', [AdminController::class, 'adminManagement'])->name('management');
    Route::post('/admin-management/add', [AdminController::class, 'addAdmin'])->name('add');
    Route::delete('/admin-management/{id}', [AdminController::class, 'removeAdmin'])->name('remove');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';