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
        'age_recommande_mois',
        'dose_numero',
        'maladie_evitee',
        'obligatoire',
        'code'
    ];

    public function doses()
    {
        return $this->hasMany(Dose::class);
    }

    public function getAgeTexteAttribute()
    {
        if ($this->age_recommande_mois == 0) {
            return 'À la naissance';
        } elseif ($this->age_recommande_mois < 12) {
            return $this->age_recommande_mois . ' mois';
        } else {
            $ans = floor($this->age_recommande_mois / 12);
            $mois = $this->age_recommande_mois % 12;
            return $ans . ' an' . ($ans > 1 ? 's' : '') . ($mois > 0 ? ' et ' . $mois . ' mois' : '');
        }
    }
}