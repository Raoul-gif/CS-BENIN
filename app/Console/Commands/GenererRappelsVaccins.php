<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enfant;
use App\Models\Rappel;
use App\Models\Vaccin;

class GenererRappelsVaccins extends Command
{
    protected $signature = 'vaccins:generer-rappels';
    protected $description = 'Génère les rappels pour les vaccins à faire';

    public function handle()
    {
        $this->info('🔍 Génération des rappels...');
        
        $enfants = Enfant::with('doses')->get();
        $compteRappels = 0;

        foreach ($enfants as $enfant) {
            $ageMois = $enfant->ageMois;
            $vaccinsAdministres = $enfant->doses->pluck('vaccin_id')->toArray();
            
            $vaccinsRecommandes = Vaccin::whereNotIn('id', $vaccinsAdministres)
                                        ->where('age_recommande_mois', '<=', $ageMois)
                                        ->get();

            foreach ($vaccinsRecommandes as $vaccin) {
                $rappelExistant = Rappel::where('enfant_id', $enfant->id)
                                        ->where('vaccin_id', $vaccin->id)
                                        ->where('envoye', false)
                                        ->first();

                if (!$rappelExistant) {
                    Rappel::create([
                        'enfant_id' => $enfant->id,
                        'vaccin_id' => $vaccin->id,
                        'date_prevue' => now(),
                        'email_destinataire' => $enfant->user->email,
                        'message' => "Rappel: {$vaccin->nom} pour {$enfant->prenom}"
                    ]);
                    
                    $compteRappels++;
                    $this->line("   ✅ Rappel créé pour {$enfant->prenom} : {$vaccin->nom}");
                }
            }
        }

        $this->info("✅ $compteRappels rappels générés !");
        return 0;
    }
}