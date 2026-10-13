<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidature extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'candidatures'; 
    protected $fillable = [ 
        'candidat_id', 
        'offre_emploi_id', 
        'date_candidature', 
        'statut', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_candidature' => 'date', 
        ]; 
    }
     /** 
     * Candidat concerné. 
     */ 
    public function candidat(): BelongsTo 
    { 
        return $this->belongsTo(Candidat::class); 
    }
     /** 
     * Offre à laquelle le candidat postule. 
     */ 
    public function offreEmploi(): BelongsTo 
    { 
        return $this->belongsTo( 
            OffreEmploi::class, 
            'offre_emploi_id' 
        ); 
    }
     /** 
     * Documents de la candidature. 
     */ 
    public function documents(): HasMany 
    { 
        return $this->hasMany(DocumentCandidature::class); 
    }
     /** 
     * Entretiens du candidat pour cette candidature. 
     */ 
    public function entretiens(): HasMany 
    { 
        return $this->hasMany(Entretien::class); 
    }
     /** 
     * Évaluations du candidat. 
     */ 
    public function evaluations(): HasMany 
    { 
        return $this->hasMany(EvaluationCandidat::class); 
    }
}
