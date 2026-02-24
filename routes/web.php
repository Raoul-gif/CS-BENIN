<?php

use App\Mail\RappelVaccin;
use App\Models\Rappel;
use Illuminate\Support\Facades\Mail;

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