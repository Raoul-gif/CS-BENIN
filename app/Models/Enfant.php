<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Enfant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nom', 'prenom', 'date_naissance', 
        'lieu_naissance', 'sexe', 'groupe_sanguin', 'photo'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rappels()
    {
        return $this->hasMany(Rappel::class);
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class);
    }

    /**
     * Calculer l'âge en mois (positif garanti)
     */
    public function getAgeMoisAttribute()
    {
        $naissance = Carbon::parse($this->date_naissance);
        $maintenant = Carbon::now();
        return (int) round($naissance->diffInMonths($maintenant));
    }

    /**
     * Formater l'âge en français (ex: 2 ans et 3 mois)
     */
    public function getAgeFormateAttribute()
    {
        $moisTotal = $this->age_mois;
        
        $ans = floor($moisTotal / 12);
        $mois = $moisTotal % 12;
        
        if ($ans > 0 && $mois > 0) {
            return $ans . ' an' . ($ans > 1 ? 's' : '') . ' et ' . $mois . ' mois';
        } elseif ($ans > 0) {
            return $ans . ' an' . ($ans > 1 ? 's' : '');
        } else {
            return $mois . ' mois';
        }
    }

    /**
     * Garder l'ancien nom pour compatibilité (optionnel)
     */
    public function getAgeEnMoisAttribute()
    {
        return $this->age_mois;
    }
}