<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entretien extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'entretiens';
     protected $fillable = [ 
        'candidature_id', 
        'evaluateur_id', 
        'date_entretien', 
        'heure_entretien', 
        'type_entretien', 
        'lieu', 
        'statut', 
        'note', 
        'commentaire', 
        'resultat', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_entretien' => 'date', 
            'note' => 'decimal:2', 
        ]; 
    }
     /** 
     * Candidature concernée. 
     */ 
    public function candidature(): BelongsTo 
    { 
        return $this->belongsTo(Candidature::class); 
    }
     /** 
     * Utilisateur responsable de l'entretien. 
     */ 
    public function evaluateur(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'evaluateur_id' 
        ); 
    }
     /** 
     * Évaluations réalisées pendant cet entretien. 
     */ 
    public function evaluations(): HasMany 
    { 
        return $this->hasMany(EvaluationCandidat::class); 
    }
}
