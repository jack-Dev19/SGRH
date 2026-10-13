<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'evaluations';
    protected $fillable = [ 
        'employe_id', 
        'evaluateur_id', 
        'niveau_appreciation_id', 
        'titre', 
        'type', 
        'periode', 
        'date_debut', 
        'date_fin', 
        'date_evaluation', 
        'score_global', 
        'appreciation', 
        'commentaire', 
        'recommandation', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
            'date_evaluation' => 'date', 
            'score_global' => 'decimal:2', 
        ]; 
    }
    /** 
     * Employé évalué. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
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
    /** 
     * Niveau d'appréciation obtenu. 
     */ 
    public function niveauAppreciation(): BelongsTo 
    { 
        return $this->belongsTo( 
            NiveauAppreciation::class, 
            'niveau_appreciation_id' 
        ); 
    }
}
