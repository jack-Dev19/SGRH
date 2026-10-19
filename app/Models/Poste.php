<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poste extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'postes';

    protected $fillable = [
        'nom',
        'description',
        'departement_id',
        'salaire_base',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'salaire_base' => 'decimal:2',
            'statut'       => 'boolean',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'poste_id');
    }

    // =================================================================
    // SCOPES & HELPERS
    // =================================================================

    public function scopeActifs($query)
    {
        return $query->where('statut', 1);
    }

    public function isActif(): bool
    {
        return (bool) $this->statut;
    }

    public function estUtilise(): bool
    {
        return $this->employes()->exists();
    }
}