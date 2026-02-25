<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    use HasFactory;

    protected $fillable = [
        'enfant_id',
        'vaccin_id',
        'date_prevue',
        'date_administration',
        'statut',
        'lot_vaccin',
        'centre_sante',
        'notes',
        'date_envoi',
        'envoye',
        'type',
        'message',
        'tentatives',
        'email_destinataire',
        'erreur'
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'date_administration' => 'date',
        'date_envoi' => 'date',
        'envoye' => 'boolean'
    ];

    public function enfant()
    {
        return $this->belongsTo(Enfant::class);
    }

    public function vaccin()
    {
        return $this->belongsTo(Vaccin::class);
    }

    /**
     * Scope pour les rappels non envoyés
     */
    public function scopeNonEnvoye($query)
    {
        return $query->where('envoye', false);
    }

    /**
     * Scope pour les rappels à envoyer aujourd'hui
     */
    public function scopeADate($query)
    {
        return $query->whereDate('date_prevue', '<=', now())
                     ->where('envoye', false);
    }

    /**
     * Vérifier si le rappel est en attente
     */
    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si le rappel est effectué
     */
    public function isEffectue()
    {
        return $this->statut === 'effectue';
    }

    /**
     * Marquer comme envoyé
     */
    public function marquerCommeEnvoye()
    {
        $this->update([
            'envoye' => true,
            'date_envoi' => now()
        ]);
    }
}