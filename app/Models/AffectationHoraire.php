<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffectationHoraire extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'affectations_horaires';
     protected $fillable = [ 
        'employe_id', 
        'horaire_id', 
        'date_debut', 
        'date_fin', 
        'statut', 
        'motif', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
        ]; 
    }
    /** 
     * Employé auquel l'horaire est affecté. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
    /** 
     * Horaire affecté à l'employé. 
     */ 
    public function horaire(): BelongsTo 
    { 
        return $this->belongsTo(HoraireTravail::class); 
    }
}
