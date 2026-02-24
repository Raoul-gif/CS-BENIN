<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getAgeEnMoisAttribute()
    {
        return $this->date_naissance->diffInMonths(now());
    }

    public function getAgeFormateAttribute()
    {
        $mois = $this->age_en_mois;
        if ($mois < 24) {
            return $mois . ' mois';
        }
        $ans = floor($mois / 12);
        $moisRestants = $mois % 12;
        return $ans . ' ans ' . ($moisRestants > 0 ? $moisRestants . ' mois' : '');
    }
}