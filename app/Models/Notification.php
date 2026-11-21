<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Modèle Notification
 * --------------------------------------------------
 * Gère les notifications personnalisées du SGRH.
 *
 * Table : notifications
 * Identifiant : UUID
 * Destinataire : User
 */
class Notification extends Model
{
    use HasUuids;

    /**
     * Champs autorisés lors de l'insertion ou modification.
     */
    protected $fillable = [
        'user_id',
        'demande_conge_id',
        'titre',
        'message',
        'type',
        'lue',
        'date_lecture',
        'url',
    ];

    /**
     * Conversion automatique des types de données.
     */
    protected function casts(): array
    {
        return [
            'lue'          => 'boolean',
            'date_lecture' => 'datetime',
        ];
    }

    /**
     * Relation : notification appartenant à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation : notification associée à une demande de congé.
     */
    public function demandeConge(): BelongsTo
    {
        return $this->belongsTo(DemandeConge::class, 'demande_conge_id');
    }

    /**
     * Scope : récupérer uniquement les notifications non lues.
     */
    public function scopeNonLues($query)
    {
        return $query->where('lue', false);
    }

    /**
     * Scope : récupérer uniquement les notifications lues.
     */
    public function scopeLues($query)
    {
        return $query->where('lue', true);
    }

    /**
     * Marquer cette notification comme lue.
     */
    public function marquerCommeLue(): bool
    {
        if ($this->lue) {
            return true;
        }

        return $this->update([
            'lue'          => true,
            'date_lecture' => now(),
        ]);
    }

    /**
     * Marquer cette notification comme non lue.
     */
    public function marquerCommeNonLue(): bool
    {
        return $this->update([
            'lue'          => false,
            'date_lecture' => null,
        ]);
    }
}
