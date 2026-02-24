<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    use HasFactory;

    protected $fillable = [
        'enfant_id', 'vaccin_id', 'date_prevue', 'date_administration',
        'statut', 'lot_vaccin', 'centre_sante', 'notes'
    ];

    protected $casts = [
        'date_prevue' => 'date',
        'date_administration' => 'date',
    ];

    public function enfant()
    {
        return $this->belongsTo(Enfant::class);
    }

    public function vaccin()
    {
        return $this->belongsTo(Vaccin::class);
    }
}