<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salaire extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'salaires';
    protected $fillable = [ 
        'employe_id', 
        'salaire_base', 
        'date_debut', 
        'date_fin', 
        'motif', 
        'statut', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'salaire_base' => 'decimal:2', 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
        ]; 
    }
     /** 
     * Employé concerné par ce salaire. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
