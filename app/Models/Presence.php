<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'presences';

    protected $fillable = [
        'employe_id',
        'date',
        'heure_entree',
        'heure_sortie',
        'heures_travaillees',
        'retard_minutes',
        'statut',
        'source',
        'commentaire',
    ];

    protected function casts(): array
    {
        return [
            'date'                => 'date',
            'heures_travaillees'  => 'float',
            'retard_minutes'      => 'integer',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }
}