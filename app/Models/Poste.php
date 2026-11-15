<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poste extends Model
{
    use HasFactory, HasUuids;

    // Nom de la table dans la base de données.
    protected $table = 'postes';

    // Champs autorisés lors de la création ou modification d'un poste.
    // Ils correspondent aux colonnes de la migration create_postes_table.
    protected $fillable = [
        'nom',
        'description',
        'niveau',
        'salaire_min',
        'salaire_max',
        'departement_id',
        'statut',
    ];

    // Conversion des types de données.
    protected function casts(): array
    {
        return [
            'salaire_min' => 'decimal:2',
            'salaire_max' => 'decimal:2',
        ];
    }

    /**
     * Un poste appartient à un département.
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    /**
     * Un poste peut être associé à plusieurs employés.
     */
    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'poste_id');
    }

    /**
     * Filtrer uniquement les postes actifs.
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Vérifier si le poste est actif.
     */
    public function isActif(): bool
    {
        return strtolower(trim((string) $this->statut)) === 'actif';
    }

    /**
     * Vérifier si le poste est déjà utilisé par un employé.
     */
    public function estUtilise(): bool
    {
        return $this->employes()->exists();
    }
}
