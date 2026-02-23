<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getAgeMoisAttribute()
    {
        return now()->diffInMonths($this->date_naissance);
    }
}