<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle User
 * -------------
 * Représente un compte utilisateur pouvant se connecter au SGRH.
 *
 * Particularités :
 * - Utilise des UUID (comme toutes les tables du seeder).
 * - Possède un champ "matricule" unique (ex: ADM001) en plus de l'email.
 * - Possède un champ "statut" (booléen) qui bloque la connexion si désactivé.
 * - Est lié à un rôle via role_id (FK vers la table roles).
 */
class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * Attributs assignables en masse.
     * IMPORTANT : "matricule", "role_id" et "statut" doivent être ici
     * sinon le seeder ne pourra pas les insérer.
     */
    protected $fillable = [
        'name',
        'email',
        'matricule',
        'role_id',
        'password',
        'statut',
    ];

    /**
     * Attributs cachés lors de la sérialisation (JSON).
     * Le mot de passe ne doit JAMAIS sortir de la BDD.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automatiques.
     * - "password" => "hashed" : hash automatiquement à l'assignation.
     * - "statut" => "boolean" : garantit un vrai booléen PHP.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'statut'            => 'boolean',
        ];
    }

    /**
     * Relation : un utilisateur appartient à un rôle.
     * Utilisé pour l'affichage et les autorisations.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relation (optionnelle) : un utilisateur peut être lié à un employé.
     * Utile si la table employes possède une clé user_id.
     */
    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    /**
     * Accesseur pratique : retourne "Prénom Nom" ou le matricule.
     */
    public function getIdentifiantAttribute(): string
    {
        return $this->matricule ?? $this->email;
    }

    /**
     * Vérifie si l'utilisateur possède un rôle donné.
     * Utilisation : $user->hasRole('Administrateur')
     */
    public function hasRole(string $nomRole): bool
    {
        return $this->role && $this->role->nom === $nomRole;
    }
}