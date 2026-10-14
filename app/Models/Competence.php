<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competence extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'competences';
    protected $fillable = [ 
        'nom', 
        'categorie', 
        'description', 
        'statut', 
    ];

    /** 
    * Employés possédant cette compétence. 
    */ 
    public function employes(): BelongsToMany 
    { 
        return $this->belongsToMany( 
            Employe::class, 
            'employes_competences', 
            'competence_id', 
            'employe_id' 
        )->withPivot([ 
            'id', 
            'niveau', 
            'score', 
            'annees_experience', 
            'date_evaluation', 
            'evalue_par', 
            'commentaire', 
            'statut', 
        ])->withTimestamps(); 
    }
    /** 
     * Enregistrements détaillés des compétences. 
     */ 
    public function employesCompetences(): HasMany 
    { 
        return $this->hasMany(EmployeCompetence::class); 
    }
}
