<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccin;
use Illuminate\Support\Facades\DB;

class VaccinSeeder extends Seeder
{
    public function run()
    {
        $vaccins = [
            // BCG
            [
                'nom' => 'BCG',
                'age_recommande_mois' => 0,
                'dose_numero' => 1,
                'maladie_evitee' => 'Tuberculose',
                'code' => 'BCG1'
            ],
            // Pentavalent (DTCoq-HepB-Hib)
            [
                'nom' => 'Pentavalent',
                'age_recommande_mois' => 2,
                'dose_numero' => 1,
                'maladie_evitee' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib',
                'code' => 'PENTA1'
            ],
            [
                'nom' => 'Pentavalent',
                'age_recommande_mois' => 4,
                'dose_numero' => 2,
                'maladie_evitee' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib',
                'code' => 'PENTA2'
            ],
            [
                'nom' => 'Pentavalent',
                'age_recommande_mois' => 6,
                'dose_numero' => 3,
                'maladie_evitee' => 'Diphtérie, Tétanos, Coqueluche, Hépatite B, Hib',
                'code' => 'PENTA3'
            ],
            // Polio
            [
                'nom' => 'Polio oral (VPO)',
                'age_recommande_mois' => 0,
                'dose_numero' => 0,
                'maladie_evitee' => 'Poliomyélite',
                'code' => 'VPO0'
            ],
            [
                'nom' => 'Polio oral (VPO)',
                'age_recommande_mois' => 2,
                'dose_numero' => 1,
                'maladie_evitee' => 'Poliomyélite',
                'code' => 'VPO1'
            ],
            [
                'nom' => 'Polio injectable (VPI)',
                'age_recommande_mois' => 4,
                'dose_numero' => 1,
                'maladie_evitee' => 'Poliomyélite',
                'code' => 'VPI1'
            ],
            // Rougeole
            [
                'nom' => 'Rougeole',
                'age_recommande_mois' => 9,
                'dose_numero' => 1,
                'maladie_evitee' => 'Rougeole',
                'code' => 'ROU1'
            ],
            [
                'nom' => 'Rougeole',
                'age_recommande_mois' => 15,
                'dose_numero' => 2,
                'maladie_evitee' => 'Rougeole',
                'code' => 'ROU2'
            ],
            // Fièvre jaune
            [
                'nom' => 'Fièvre jaune',
                'age_recommande_mois' => 9,
                'dose_numero' => 1,
                'maladie_evitee' => 'Fièvre jaune',
                'code' => 'FJ1'
            ],
        ];

        foreach ($vaccins as $vaccin) {
            Vaccin::create($vaccin);
        }
    }
}