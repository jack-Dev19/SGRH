<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employe extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employes';
    protected $fillable = ['user_id','departement_id','poste_id','matricule',
    'nom','prenom','sexe','date_naissance','telephone','email','adresse',
    'date_embauche','date_sortie','statut','photo',];

    protected function casts(): array 
    { 
        return [ 
            'date_naissance' => 'date', 
            'date_embauche' => 'date', 
            'date_sortie' => 'date', 
        ]; 
    } 
    /** 
     * Compte utilisateur associé. 
     */ 
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    } 
    /** 
     * Département de l'employé. 
     */ 
    public function departement(): BelongsTo 
    { 
        return $this->belongsTo(Departement::class); 
    } 
    /** 
     * Poste de l'employé. 
     */ 
    public function poste(): BelongsTo 
    { 
        return $this->belongsTo(Poste::class); 
    } 
    /** 
     * Contrats de l'employé. 
     */ 
    public function contrats(): HasMany 
    { 
        return $this->hasMany(Contrat::class); 
    }
    /** 
     * Documents de l'employé. 
     */ 
    public function documentsEmployes(): HasMany 
    { 
        return $this->hasMany(DocumentEmploye::class); 
    } 
     /** 
     * Formations de l'employé. 
     */ 
    public function formations(): BelongsToMany 
    { 
        return $this->belongsToMany( 
            Formation::class, 
            'employes_formations', 
            'employe_id', 
            'formation_id' 
        )->withPivot([ 
            'id', 
            'date_inscription', 
            'date_debut', 
            'date_fin', 
            'statut', 
            'note', 
            'resultat', 
            'certificat', 
            'fichier_certificat', 
            'commentaire', 
        ])->withTimestamps(); 
    } 
     /** 
     * Inscriptions détaillées aux formations. 
     */ 
    public function employesFormations(): HasMany 
    { 
        return $this->hasMany(EmployeFormation::class); 
    }
    /** 
     * Compétences de l'employé. 
     */ 
    public function competences(): BelongsToMany 
    { 
        return $this->belongsToMany( 
            Competence::class, 
            'employes_competences', 
            'employe_id', 
            'competence_id' 
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
    /** 
     * Évaluations de l'employé. 
     */ 
    public function evaluations(): HasMany 
    { 
        return $this->hasMany(Evaluation::class); 
    }
     /** 
     * Demandes de congés. 
     */ 
    public function demandesConges(): HasMany 
    { 
        return $this->hasMany(DemandeConge::class); 
    } 
    /** 
     * Absences. 
     */ 
    public function absences(): HasMany 
    { 
        return $this->hasMany(Absence::class); 
    } 
    /** 
     * Présences. 
     */ 
    public function presences(): HasMany 
    { 
        return $this->hasMany(Presence::class); 
    } 
    /** 
     * Pointages. 
     */ 
    public function pointages(): HasMany 
    { 
        return $this->hasMany(Pointage::class); 
    }
     /** 
     * Affectations d'horaires. 
     */ 
    public function affectationsHoraires(): HasMany 
    { 
        return $this->hasMany(AffectationHoraire::class); 
    } 
     /** 
     * Salaires. 
     */ 
    public function salaires(): HasMany 
    { 
        return $this->hasMany(Salaire::class); 
    } 
     /** 
     * Primes. 
     */ 
    public function primes(): HasMany 
    { 
        return $this->hasMany(Prime::class); 
    } 
    /** 
     * Retenues. 
     */ 
    public function retenues(): HasMany 
    { 
        return $this->hasMany(Retenue::class); 
    } 
     /** 
     * Heures supplémentaires. 
     */ 
    public function heuresSupplementaires(): HasMany 
    { 
        return $this->hasMany(HeureSupplementaire::class); 
    } 
     /** 
     * Bulletins de paie. 
     */ 
    public function bulletinsPaie(): HasMany 
    { 
        return $this->hasMany(BulletinPaie::class); 
    } 
     /** 
     * Sanctions. 
     */ 
    public function sanctions(): HasMany 
    { 
        return $this->hasMany(Sanction::class); 
    }
     /** 
     * Département dont l'employé est responsable. 
     */ 
    public function departementResponsable(): HasOne 
    { 
        return $this->hasOne( 
            Departement::class, 
            'responsable_id' 
        ); 
    } 
}
