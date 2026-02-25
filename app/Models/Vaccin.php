<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'description', 
        'age_min_mois', 
        'age_max_mois', 
        'dose_numero', 
        'maladie_previent',
        'date_debut',   // AJOUTÉ
        'date_fin',     // AJOUTÉ
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function rappels()
    {
        return $this->hasMany(Rappel::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }
}