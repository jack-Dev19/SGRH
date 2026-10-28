<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OffreEmploi extends Model
{
    use SoftDeletes;

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
        'deadline',
        'email_contact',
        'statut',
        'publiee_le',
        'created_by',
    ];

    protected $casts = [
        'deadline' => 'date',
        'publiee_le' => 'datetime',
    ];

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offre) {
            if (empty($offre->slug)) {
                $offre->slug = Str::slug($offre->titre)
                    . '-' . Str::random(6);
            }
        });
    }

    // ==================== SCOPES ====================

    public function scopePubliees($query)
    {
        return $query
            ->where('statut', 'publiee')
            ->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhereDate(
                        'deadline',
                        '>=',
                        now()->toDateString()
                    );
            })
            ->orderByDesc('publiee_le')
            ->orderByDesc('created_at');
    }

    // ==================== ACCESSEURS ====================

    public function getBadgeColorAttribute(): string
    {
        return match ($this->type_contrat) {
            'CDI' => 'purple',
            'CDD' => 'pink',
            'Stage' => 'blue',
            'Freelance' => 'emerald',
            'Intérim' => 'amber',
            default => 'slate',
        };
    }

    public function getEstExpireeAttribute(): bool
    {
        return $this->deadline !== null
            && $this->deadline->lt(today());
    }

    public function getJoursRestantsAttribute(): ?int
    {
        if (!$this->deadline) {
            return null;
        }

        return (int) today()->diffInDays(
            $this->deadline,
            false
        );
    }

    // ==================== RELATIONS ====================

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
