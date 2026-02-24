<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueVaccin extends Model
{
    protected $table = 'historique_vaccins';
    
    protected $fillable = [
        'enfant_id', 'vaccin_id', 'date_administration', 'lieu_administration',
        'professionnel_sante', 'lot_vaccin', 'prochain_rappel', 'notes',
        'document_justificatif'
    ];

    protected $casts = [
        'date_administration' => 'date',
        'prochain_rappel' => 'date'
    ];

    public function enfant(): BelongsTo
    {
        return $this->belongsTo(Enfant::class);
    }

    public function vaccin(): BelongsTo
    {
        return $this->belongsTo(Vaccin::class);
    }

    // Scope pour les vaccins à venir
    public function scopeAVenir($query)
    {
        return $query->whereNotNull('prochain_rappel')
                     ->where('prochain_rappel', '>=', now());
    }
}