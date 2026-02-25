<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccin;

class VaccinSeeder extends Seeder
{
    public function run()
    {
        $vaccins = [
            // Naissance
            ['nom' => 'BCG', 'description' => 'Vaccin contre la tuberculose', 'age_min_mois' => 0, 'age_max_mois' => 0, 'dose_numero' => 1, 'maladie_previent' => 'Tuberculose'],
            ['nom' => 'VPO 0', 'description' => 'Vaccin Polio Oral (naissance)', 'age_min_mois' => 0, 'age_max_mois' => 0, 'dose_numero' => 0, 'maladie_previent' => 'Poliomyélite'],
            
            // 6-8 semaines (1.5-2 mois)
            ['nom' => 'DTC-HepB-Hib 1', 'description' => 'Pentavalent 1ère dose', 'age_min_mois' => 1.5, 'age_max_mois' => 2, 'dose_numero' => 1, 'maladie_previent' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib'],
            ['nom' => 'VPO 1', 'description' => 'Vaccin Polio Oral 1ère dose', 'age_min_mois' => 1.5, 'age_max_mois' => 2, 'dose_numero' => 1, 'maladie_previent' => 'Poliomyélite'],
            ['nom' => 'Pneumocoque 1', 'description' => 'Vaccin contre le pneumocoque 1ère dose', 'age_min_mois' => 1.5, 'age_max_mois' => 2, 'dose_numero' => 1, 'maladie_previent' => 'Infections à pneumocoque'],
            ['nom' => 'Rotavirus 1', 'description' => 'Vaccin contre le rotavirus 1ère dose', 'age_min_mois' => 1.5, 'age_max_mois' => 2, 'dose_numero' => 1, 'maladie_previent' => 'Gastro-entérite à rotavirus'],
            
            // 10-14 semaines (2.5-3.5 mois)
            ['nom' => 'DTC-HepB-Hib 2', 'description' => 'Pentavalent 2ème dose', 'age_min_mois' => 2.5, 'age_max_mois' => 3.5, 'dose_numero' => 2, 'maladie_previent' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib'],
            ['nom' => 'VPO 2', 'description' => 'Vaccin Polio Oral 2ème dose', 'age_min_mois' => 2.5, 'age_max_mois' => 3.5, 'dose_numero' => 2, 'maladie_previent' => 'Poliomyélite'],
            ['nom' => 'Pneumocoque 2', 'description' => 'Vaccin contre le pneumocoque 2ème dose', 'age_min_mois' => 2.5, 'age_max_mois' => 3.5, 'dose_numero' => 2, 'maladie_previent' => 'Infections à pneumocoque'],
            ['nom' => 'Rotavirus 2', 'description' => 'Vaccin contre le rotavirus 2ème dose', 'age_min_mois' => 2.5, 'age_max_mois' => 3.5, 'dose_numero' => 2, 'maladie_previent' => 'Gastro-entérite à rotavirus'],
            
            // 14-16 semaines (3.5-4 mois)
            ['nom' => 'DTC-HepB-Hib 3', 'description' => 'Pentavalent 3ème dose', 'age_min_mois' => 3.5, 'age_max_mois' => 4, 'dose_numero' => 3, 'maladie_previent' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib'],
            ['nom' => 'VPO 3', 'description' => 'Vaccin Polio Oral 3ème dose', 'age_min_mois' => 3.5, 'age_max_mois' => 4, 'dose_numero' => 3, 'maladie_previent' => 'Poliomyélite'],
            ['nom' => 'Pneumocoque 3', 'description' => 'Vaccin contre le pneumocoque 3ème dose', 'age_min_mois' => 3.5, 'age_max_mois' => 4, 'dose_numero' => 3, 'maladie_previent' => 'Infections à pneumocoque'],
            
            // 9 mois
            ['nom' => 'VAR', 'description' => 'Vaccin Anti-Rougeoleux', 'age_min_mois' => 9, 'age_max_mois' => 12, 'dose_numero' => 1, 'maladie_previent' => 'Rougeole'],
            ['nom' => 'VAA', 'description' => 'Vaccin Anti-Amaril (Fièvre Jaune)', 'age_min_mois' => 9, 'age_max_mois' => 12, 'dose_numero' => 1, 'maladie_previent' => 'Fièvre Jaune'],
            
            // 15-18 mois
            ['nom' => 'DTC-HepB-Hib 4', 'description' => 'Rappel pentavalent', 'age_min_mois' => 15, 'age_max_mois' => 18, 'dose_numero' => 4, 'maladie_previent' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib'],
            ['nom' => 'VAR 2', 'description' => 'Rappel rougeole', 'age_min_mois' => 15, 'age_max_mois' => 18, 'dose_numero' => 2, 'maladie_previent' => 'Rougeole'],
        ];

        foreach ($vaccins as $vaccin) {
            Vaccin::create($vaccin);
        }
    }
}