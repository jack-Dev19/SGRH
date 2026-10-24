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
 * -----------------------------------------
 * Représente un employé du SGRH.
 *
 * Relations principales :
 * - user()               : compte de connexion
 * - departement()        : département
 * - poste()              : poste occupé
 * - contrats()           : contrats de travail
 * - qrCodes()            : QR codes de pointage
 * - qrCodeActif()        : QR code actif (celui utilisé pour pointer)
 * - pointages()          : pointages (arrivées/départs)
 * - absences()           : absences
 * - demandesConges()     : demandes de congé
 * - presences()          : présences quotidiennes
 * - salaires()           : salaires
 * - bulletinsPaie()      : bulletins de paie
 * - evaluations()        : évaluations de performance
 */
class Employe extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employes';

    protected $fillable = [
        'user_id',
        'departement_id',
        'poste_id',
        'matricule',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'date_embauche',
        'date_sortie',
        'statut',
        'photo',
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
    // RELATIONS DE BASE
    // =================================================================

    /**
     * ⭐ Compte utilisateur associé (pour la connexion).
     * Clé étrangère : employes.user_id → users.id
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Département de l'employé.
     * Clé étrangère : employes.departement_id → departements.id
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    /**
     * Poste occupé.
     * Clé étrangère : employes.poste_id → postes.id
     */
    public function poste(): BelongsTo
    {
        return $this->belongsTo(Poste::class, 'poste_id');
    }

    /**
     * Contrats de travail.
     */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : QR CODES & POINTAGE
    // =================================================================

    /**
     * Tous les QR codes de l'employé (actifs et révoqués).
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class, 'employe_id');
    }

    /**
     * QR code ACTIF de l'employé (celui utilisé pour pointer).
     */
    public function qrCodeActif(): HasOne
    {
        return $this->hasOne(QrCode::class, 'employe_id')
            ->where('actif', true)
            ->latestOfMany();
    }

    /**
     * Pointages de l'employé (arrivées et départs).
     */
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : ABSENCES & CONGÉS
    // =================================================================

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'employe_id');
    }

    public function demandesConges(): HasMany
    {
        return $this->hasMany(DemandeConge::class, 'employe_id');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : DOCUMENTS & FORMATIONS
    // =================================================================

    public function documentsEmployes(): HasMany
    {
        return $this->hasMany(DocumentEmploye::class, 'employe_id');
    }

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

    public function employesFormations(): HasMany
    {
        return $this->hasMany(EmployeFormation::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : COMPÉTENCES & ÉVALUATIONS
    // =================================================================

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

    public function employesCompetences(): HasMany
    {
        return $this->hasMany(EmployeCompetence::class, 'employe_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : HORAIRES
    // =================================================================

    public function affectationsHoraires(): HasMany
    {
        return $this->hasMany(AffectationHoraire::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : PAIE
    // =================================================================

    public function salaires(): HasMany
    {
        return $this->hasMany(Salaire::class, 'employe_id');
    }

    public function primes(): HasMany
    {
        return $this->hasMany(Prime::class, 'employe_id');
    }

    public function retenues(): HasMany
    {
        return $this->hasMany(Retenue::class, 'employe_id');
    }

    public function heuresSupplementaires(): HasMany
    {
        return $this->hasMany(HeureSupplementaire::class, 'employe_id');
    }

    public function bulletinsPaie(): HasMany
    {
        return $this->hasMany(BulletinPaie::class, 'employe_id');
    }

    public function sanctions(): HasMany
    {
        return $this->hasMany(Sanction::class, 'employe_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'employe_id');
    }

    // =================================================================
    // RELATIONS : RESPONSABILITÉS
    // =================================================================

    /**
     * ⭐ Département dont l'employé est responsable.
     *
     * ⚠️ ATTENTION : Cette relation n'a de sens QUE si le département
     * utilise `employes.id` comme `responsable_id`.
     *
     * ⚠️ Dans ton projet, `departements.responsable_id` pointe vers `users.id`,
     * PAS vers `employes.id`. Donc cette relation est probablement
     * à NE PAS utiliser. Utilise plutôt `$employe->user->departementsResponsables`.
     *
     * Conservée ici pour compatibilité historique.
     */
    public function departementResponsable(): HasOne
    {
        return $this->hasOne(Departement::class, 'responsable_id', 'user_id');
    }

    // =================================================================
    // ACCESSORS
    // =================================================================

    /**
     * Nom complet : "Prénom Nom".
     */
    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Initiales : "AR" pour "Andry Rakoto".
     */
    public function getInitialesAttribute(): string
    {
        return strtoupper(
            substr($this->prenom ?? '', 0, 1) . substr($this->nom ?? '', 0, 1)
        );
    }
}