<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OffreEmploi extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'offre_emplois';

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'missions',
        'profil_recherche',
        'type_contrat',
        'lieu',
        'experience',
        'niveau_etude',
        'salaire',
        'badge_label',
        'email_contact',
        'statut',
        'date_limite',       // ⭐ Renommé
        'publiee_le',
        'created_by',
    ];

    protected $casts = [
        'date_limite' => 'date',      // ⭐ Renommé
        'publiee_le'  => 'datetime',
    ];

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offre) {
            if (empty($offre->slug)) {
                $offre->slug = Str::slug($offre->titre) . '-' . Str::random(6);
            }
        });
    }

    // ==================== SCOPES ====================

    public function scopePubliees($query)
    {
        return $query->where('statut', 'publiee')
                     ->where(function ($q) {
                         $q->whereNull('date_limite')                       // ⭐ Renommé
                           ->orWhere('date_limite', '>=', now()->toDateString()); // ⭐ Renommé
                     })
                     ->orderByDesc('publiee_le')
                     ->orderByDesc('created_at');
    }

    // ==================== ACCESSEURS ====================

    public function getBadgeColorAttribute(): string
    {
        return match ($this->type_contrat) {
            'CDI'       => 'purple',
            'CDD'       => 'pink',
            'Stage'     => 'blue',
            'Freelance' => 'emerald',
            'Intérim'   => 'amber',
            default     => 'slate',
        };
    }

    public function getEstExpireeAttribute(): bool
    {
        return $this->date_limite && $this->date_limite->isPast();     // ⭐ Renommé
    }

    public function getJoursRestantsAttribute(): ?int
    {
        if (!$this->date_limite) {                                     // ⭐ Renommé
            return null;
        }

        return max(0, now()->diffInDays($this->date_limite, false));   // ⭐ Renommé
    }

    // ==================== RELATIONS ====================

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}