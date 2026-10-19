<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'departements';

    protected $fillable = [
        'nom',
        'description',
        'responsable_id',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'statut' => 'boolean',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class, 'departement_id');
    }

    public function postes(): HasMany
    {
        return $this->hasMany(Poste::class, 'departement_id');
    }

    /**
     * ⭐ Postes actifs uniquement.
     */
    public function postesActifs(): HasMany
    {
        return $this->hasMany(Poste::class, 'departement_id')
            ->where('statut', 1);
    }
}