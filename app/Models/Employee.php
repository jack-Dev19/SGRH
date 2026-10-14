<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modèle Employe — table `employes` (UUID)
 */
class Employe extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employes';

    protected $fillable = [
        'user_id', 'departement_id', 'poste_id', 'matricule',
        'nom', 'prenom', 'sexe', 'date_naissance', 'telephone', 'email',
        'adresse', 'date_embauche', 'date_sortie', 'statut', 'photo',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'date_embauche'  => 'date',
            'date_sortie'    => 'date',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }

    public function poste(): BelongsTo
    {
        return $this->belongsTo(Poste::class);
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class);
    }

    public function documentsEmployes(): HasMany
    {
        return $this->hasMany(DocumentEmploye::class);
    }

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(
            Formation::class,
            'employes_formations',
            'employe_id',
            'formation_id'
        )->withPivot([
            'id', 'date_inscription', 'date_debut', 'date_fin',
            'statut', 'note', 'resultat', 'certificat',
            'fichier_certificat', 'commentaire',
        ])->withTimestamps();
    }

    public function employesFormations(): HasMany
    {
        return $this->hasMany(EmployeFormation::class);
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(
            Competence::class,
            'employes_competences',
            'employe_id',
            'competence_id'
        )->withPivot([
            'id', 'niveau', 'score', 'annees_experience',
            'date_evaluation', 'evalue_par', 'commentaire', 'statut',
        ])->withTimestamps();
    }

    public function employesCompetences(): HasMany
    {
        return $this->hasMany(EmployeCompetence::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function demandesConges(): HasMany
    {
        return $this->hasMany(DemandeConge::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class);
    }

    public function affectationsHoraires(): HasMany
    {
        return $this->hasMany(AffectationHoraire::class);
    }

    public function salaires(): HasMany
    {
        return $this->hasMany(Salaire::class);
    }

    public function primes(): HasMany
    {
        return $this->hasMany(Prime::class);
    }

    public function retenues(): HasMany
    {
        return $this->hasMany(Retenue::class);
    }

    public function heuresSupplementaires(): HasMany
    {
        return $this->hasMany(HeureSupplementaire::class);
    }

    public function bulletinsPaie(): HasMany
    {
        return $this->hasMany(BulletinPaie::class);
    }

    public function sanctions(): HasMany
    {
        return $this->hasMany(Sanction::class);
    }

    public function departementResponsable(): HasOne
    {
        return $this->hasOne(Departement::class, 'responsable_id');
    }

    // =================================================================
    // ACCESSORS
    // =================================================================

    /**
     * Nom complet : "Prénom Nom".
     * Utilisation : $employe->nom_complet
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Initiales : "AR" pour "Andry Rakoto".
     * Utilisation : $employe->initiales
     */
    public function getInitialesAttribute(): string
    {
        return strtoupper(
            substr($this->prenom ?? '', 0, 1) . substr($this->nom ?? '', 0, 1)
        );
    }
}