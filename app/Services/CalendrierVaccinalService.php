<?php
namespace App\Services;

use App\Models\Vaccin;
use App\Models\Enfant;
use App\Models\Rappel;
use Carbon\Carbon;

class CalendrierVaccinalService
{
    /**
     * Génère automatiquement les rappels pour un enfant
     * basé sur sa date de naissance et le calendrier PEV Bénin
     */
    public function genererRappelsPourEnfant(Enfant $enfant)
    {
        $dateNaissance = $enfant->date_naissance;
        $vaccins = Vaccin::all();
        
        foreach ($vaccins as $vaccin) {
            // Calculer la date prévue du vaccin
            $ageEnMois = $vaccin->age_min_mois;
            $datePrevue = $dateNaissance->copy()->addMonths((int)$ageEnMois);
            
            // Vérifier si le rappel existe déjà
            $existe = Rappel::where('enfant_id', $enfant->id)
                ->where('vaccin_id', $vaccin->id)
                ->exists();
            
            if (!$existe) {
                // Créer le rappel
                Rappel::create([
                    'enfant_id' => $enfant->id,
                    'vaccin_id' => $vaccin->id,
                    'date_prevue' => $datePrevue,
                    'statut' => 'en_attente'
                ]);
            }
        }
    }

    /**
     * Récupère le calendrier complet pour un enfant
     */
    public function getCalendrierEnfant(Enfant $enfant)
    {
        $rappels = $enfant->rappels()->with('vaccin')->get();
        
        return [
            'a_venir' => $rappels->where('statut', 'en_attente')->values(),
            'effectues' => $rappels->where('statut', 'effectue')->values(),
            'reportes' => $rappels->where('statut', 'reporte')->values(),
        ];
    }

    /**
     * Vérifie si un vaccin est en retard
     */
    public function estEnRetard(Rappel $rappel)
    {
        if ($rappel->statut !== 'en_attente') {
            return false;
        }
        
        return $rappel->date_prevue->isPast();
    }

    /**
     * Récupère les rappels à envoyer aujourd'hui
     */
    public function getRappelsDuJour()
    {
        return Rappel::where('statut', 'en_attente')
            ->whereDate('date_prevue', now())
            ->with(['enfant.parent', 'vaccin'])
            ->get();
    }
}