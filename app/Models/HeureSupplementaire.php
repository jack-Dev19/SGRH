<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeureSupplementaire extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'heures_supplementaires';
    protected $fillable = [ 
        'employe_id', 
        'date', 
        'nombre_heures', 
        'taux', 
        'montant', 
        'statut', 
        'motif', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date' => 'date', 
            'nombre_heures' => 'decimal:2', 
            'taux' => 'decimal:2', 
            'montant' => 'decimal:2', 
        ]; 
    }
     /** 
     * Employé ayant effectué les heures supplémentaires. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
