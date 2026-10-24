<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'matricule',
        'role_id',
        'password',
        'statut',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'statut'            => 'boolean',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Employé lié par matricule.
     */
    public function employe(): HasOne
    {
        return $this->hasOne(Employe::class, 'matricule', 'matricule');
    }

    public function departementsResponsables(): HasMany
    {
        return $this->hasMany(Departement::class, 'responsable_id', 'id');
    }

    // =================================================================
    // HELPERS DE RÔLE
    // =================================================================

    public function hasRole(string $nomRole): bool
    {
        return $this->role && $this->role->nom === $nomRole;
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->nom, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrateur');
    }

    public function isRH(): bool
    {
        return $this->hasRole('Responsable RH');
    }

    public function isChef(): bool
    {
        return $this->hasRole('Chef de département');
    }

    public function isEmploye(): bool
    {
        return $this->hasRole('Employé');
    }

    public function estActif(): bool
    {
        return (bool) $this->statut;
    }
}