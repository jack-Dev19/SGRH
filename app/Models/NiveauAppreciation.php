<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NiveauAppreciation extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'niveaux_appreciation';
    protected $fillable = [ 
        'nom', 
        'description', 
        'score_minimum', 
        'score_maximum', 
        'ordre', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'score_minimum' => 'decimal:2', 
            'score_maximum' => 'decimal:2', 
            'ordre' => 'integer', 
        ]; 
    }
    /** 
     * Évaluations correspondant à ce niveau d'appréciation. 
     */ 
    public function evaluations(): HasMany 
    { 
        return $this->hasMany(Evaluation::class); 
    }
}
