<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'presences';
    protected $fillable = [ 
        'employe_id', 
        'date', 
        'heure_entree', 
        'heure_sortie', 
        'statut', 
        'retard_minutes', 
        'heures_travaillees', 
        'reference_pointage', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date' => 'date', 
            'retard_minutes' => 'integer', 
            'heures_travaillees' => 'decimal:2', 
        ]; 
    } 
    /** 
    * Employé concerné par cette présence. 
    */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
