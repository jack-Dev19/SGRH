<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Departement — table `departements` (UUID)
 */
class Departement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'departements';

    protected $fillable = [
        'nom', 'description', 'statut', 'responsable_id',
    ];

    protected function casts(): array
    {
        return [
            'responsable_id' => 'string',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function postes(): HasMany
    {
        return $this->hasMany(Poste::class);
    }

    public function employes(): HasMany
    {
        return $this->hasMany(Employe::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'responsable_id');
    }
}
