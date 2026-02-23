<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dose extends Model
{
    use HasFactory;

    protected $fillable = [
        'enfant_id',
        'vaccin_id',
        'date_administration',
        'lieu_administration',
        'lot',
        'administrateur',
        'notes'
    ];

    protected $casts = [
        'date_administration' => 'date'
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