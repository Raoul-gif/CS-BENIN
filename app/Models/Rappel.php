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

    public function scopeNonEnvoye($query)
    {
        return $query->where('envoye', false);
    }

    public function scopeADate($query)
    {
        return $query->whereDate('date_prevue', '<=', now())
                     ->where('envoye', false);
    }
}