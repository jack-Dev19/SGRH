<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;
    protected $table = 'users';
    protected $fillable = [
        'role_id',
        'nom',
        'email',
        'password',
        'statut',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation : un utilisateur appartient à un rôle
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    // L'emplyé associé à cet utilisateur
    public function employe(): HasOne {
        return $this->hasOne(Employe::class);
    }
    // Documents employés téléversés par cet utilisateur
    public function documentsEmployesTeleverses(): HasMany {
        return $this->hasMany(DocumentEmploye::class,'uploaded_by');
    }
    // Demandes de congés validées par cet utilisateur.
    public function demandesCongesValidees(): HasMany {
        return $this->hasMany(DemandeConge::class,'valide_par');
    }
    // Absences validées par cet utilisateur.
    public function absencesValidees(): HasMany {
        return $this->hasMany(Absence::class,'valide_par');
    }
    // Evaluations réalisées par cet utilisateur.
    public function evaluations(): HasMany {
        return $this->hasMany(Evaluation::class,'evaluateur_id');
    }
    // compétences évaluées par cet utilisateur.
    public function competencesEvaluees(): HasMany {
        return $this->hasMany(EmployeCompetence::class,'evalue_par');
    }
    // Entretien évalués par cet utilisateur.
    public function entretiens(): HasMany {
        return $this->hasMany(Entretien::class,'evaluateur_id');
    }
    // Evaluations de candidats réalisées par cet utilisateur.
    public function evaluationsCandidats(): HasMany {
        return $this->hasMany(EvaluationCandidat::class,'evaluateur_id');
    }
    // Rapports créée par cet utilisateur.
    public function rapports(): HasMany {
        return $this->hasMany(Rapport::class);
    }
    // Notifications de l'utilisateur.
    public function notifications(): HasMany {
        return $this->hasMany(Notification::class);
    }
    // Historique des actions réalisées par l'utilisateur.
    public function historiquesActions(): HasMany {
        return $this->hasMany(HistoriqueAction::class);
    }
    // Sanctions enregistrées par cet utilisateur.
    public function sanctions(): HasMany {
        return $this->hasMany(Sanction::class);
    }
}