<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Enfant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'nom_mere',
        'nom_pere',
        'telephone_urgence',
        'groupe_sanguin',
        'photo'
    ];

    protected $casts = [
        'date_naissance' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doses()
    {
        return $this->hasMany(Dose::class);
    }

    // ✅ VERSION CORRIGÉE - GARANTIE POSITIVE
    public function getAgeMoisAttribute()
    {
        $naissance = Carbon::parse($this->date_naissance);
        $maintenant = Carbon::now();
        return (int) round($naissance->diffInMonths($maintenant));
    }
    
    // ✅ MÉTHODE UTILE POUR LA PRÉSENTATION
    public function getAgeFormateAttribute()
    {
        $moisTotal = $this->ageMois;
        
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
}