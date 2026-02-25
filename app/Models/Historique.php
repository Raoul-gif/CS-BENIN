<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $fillable = [
        'enfant_id', 'vaccin_id', 'date_administration',
        'lieu_administration', 'professionnel_sante', 'lot_vaccin', 'notes'
    ];

    protected $casts = [
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