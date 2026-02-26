<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carnet extends Model
{
    protected $fillable = [
        'enfant_id', 'fichier_pdf', 'hash', 'signature',
        'cachet', 'statut', 'date_generation', 'date_expiration'
    ];

    protected $casts = [
        'date_generation' => 'datetime',
        'date_expiration' => 'datetime'

    ];

    public function enfant()
    {
        return $this->belongsTo(Enfant::class);
    }

    // Vérifier l'intégrité du PDF
    public function verifierIntegrite(): bool
    {
        if (!file_exists(storage_path("app/public/{$this->fichier_pdf}"))) {
            return false;
        }
        
        $currentHash = hash_file('sha256', storage_path("app/public/{$this->fichier_pdf}"));
        return $currentHash === $this->hash;
    }
}