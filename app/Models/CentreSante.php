<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreSante extends Model
{
    use HasFactory;

    protected $table = 'centres_sante';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'telephone',
        'email',
        'responsable',
        'horaires',
        'latitude',
        'longitude',
        'actif',
        'description',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Relation avec les rappels (vaccins effectués dans ce centre)
     */
    public function rappels()
    {
        return $this->hasMany(Rappel::class, 'centre_sante_id');
    }

    /**
     * Scope pour les centres actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Nombre de vaccins effectués dans ce centre
     */
    public function getVaccinsEffectuesAttribute()
    {
        return $this->rappels()->count();
    }
}