<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeCompetence extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employes_competences';
     protected $fillable = [ 
        'employe_id', 
        'competence_id', 
        'niveau', 
        'score', 
        'annees_experience', 
        'date_evaluation', 
        'evalue_par', 
        'commentaire', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'score' => 'decimal:2', 
            'annees_experience' => 'decimal:2', 
            'date_evaluation' => 'date', 
        ]; 
    }
    /** 
    * Employé concerné.
    */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }
    /** 
    * Compétence concernée. 
    */ 
    public function competence(): BelongsTo 
    { 
        return $this->belongsTo(Competence::class); 
    }
    /** 
    * Utilisateur ayant évalué la compétence. 
    */ 
    public function evaluateur(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'evalue_par' 
        ); 
    }
}
