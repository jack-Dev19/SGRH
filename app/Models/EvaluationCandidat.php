<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationCandidat extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'evaluations_candidats';
     protected $fillable = [ 
        'candidature_id', 
        'entretien_id', 
        'evaluateur_id', 
        'competences_techniques', 
        'communication', 
        'motivation', 
        'experience', 
        'note_globale', 
        'recommandation', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'competences_techniques' => 'decimal:2', 
            'communication' => 'decimal:2', 
            'motivation' => 'decimal:2', 
            'experience' => 'decimal:2', 
            'note_globale' => 'decimal:2', 
        ]; 
    }
     /** 
     * Candidature évaluée. 
     */ 
    public function candidature(): BelongsTo 
    { 
        return $this->belongsTo(Candidature::class); 
    }
     /** 
     * Entretien concerné. 
     */ 
    public function entretien(): BelongsTo 
    { 
        return $this->belongsTo(Entretien::class); 
    }
     /** 
     * Utilisateur ayant réalisé l'évaluation. 
     */ 
    public function evaluateur(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'evaluateur_id' 
        ); 
    }
}
