<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraireJour extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'horaires_jours';
     protected $fillable = [ 
        'horaire_id', 
        'jour_semaine', 
        'est_travaille', 
        'heure_debut', 
        'heure_fin', 
        'debut_pause', 
        'fin_pause', 
        'heures_prevues', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'jour_semaine' => 'integer', 
            'est_travaille' => 'boolean', 
            'heures_prevues' => 'decimal:2', 
        ]; 
    }
     /** 
     * Horaire de travail auquel appartient ce jour. 
     */ 
    public function horaire(): BelongsTo 
    { 
        return $this->belongsTo(HoraireTravail::class); 
    } 
}
