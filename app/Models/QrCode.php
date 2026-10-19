<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle QrCode — table `qr_codes` (UUID)
 * ---------------------------------------
 * Représente un QR code unique associé à un employé pour le pointage.
 *
 * Règles métier :
 * - Un employé peut avoir plusieurs QR codes dans son historique.
 * - MAIS un seul actif à la fois (actif = true).
 * - Pour révoquer → actif = false + revoque_le + raison_revocation.
 */
class QrCode extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'qr_codes';

    protected $fillable = [
        'employe_id',
        'code',
        'token',
        'contenu_qr',
        'actif',
        'genere_par',
        'genere_le',
        'revoque_le',
        'revoque_par',
        'raison_revocation',
        'scan_count',
        'dernier_scan_le',
    ];

    protected function casts(): array
    {
        return [
            'actif'           => 'boolean',
            'genere_le'       => 'datetime',
            'revoque_le'      => 'datetime',
            'dernier_scan_le' => 'datetime',
            'scan_count'      => 'integer',
        ];
    }

    // =================================================================
    // RELATIONS
    // =================================================================

    /**
     * Employé propriétaire du QR code.
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class);
    }

    /**
     * Utilisateur qui a généré le QR code.
     */
    public function generateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'genere_par');
    }

    /**
     * Utilisateur qui a révoqué le QR code.
     */
    public function revocateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoque_par');
    }

    /**
     * Pointages effectués avec ce QR code.
     */
    public function pointages(): HasMany
    {
        return $this->hasMany(Pointage::class);
    }

    // =================================================================
    // SCOPES
    // =================================================================

    /**
     * Scope : QR codes actifs uniquement.
     * Utilisation : QrCode::actif()->get()
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope : QR codes révoqués.
     */
    public function scopeRevoque($query)
    {
        return $query->where('actif', false)->whereNotNull('revoque_le');
    }

    // =================================================================
    // HELPERS
    // =================================================================

    /**
     * Vérifier si le QR code est valide (actif).
     */
    public function isValid(): bool
    {
        return $this->actif && $this->revoque_le === null;
    }

    /**
     * Incrémenter le compteur de scans.
     */
    public function incrementerScan(): void
    {
        $this->increment('scan_count');
        $this->update(['dernier_scan_le' => now()]);
    }
}