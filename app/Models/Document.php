<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'employe_id',
        'contrat_id',
        'categorie',
        'titre',
        'description',
        'fichier_path',
        'fichier_nom_original',
        'fichier_mime',
        'fichier_taille',
        'mois',
        'annee',
        'uploaded_by',
    ];

    protected $casts = [
        'fichier_taille' => 'integer',
        'mois'           => 'integer',
        'annee'          => 'integer',
    ];

    // ==================== RELATIONS ====================

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ==================== ACCESSEURS ====================

    public function getTailleHumaineAttribute(): string
    {
        $octets = $this->fichier_taille;

        if ($octets < 1024) {
            return $octets . ' o';
        }
        if ($octets < 1024 * 1024) {
            return round($octets / 1024, 1) . ' Ko';
        }

        return round($octets / (1024 * 1024), 2) . ' Mo';
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->fichier_path);
    }

    public function getIconeAttribute(): string
    {
        return match (true) {
            str_contains($this->fichier_mime, 'image') => 'image',
            str_contains($this->fichier_mime, 'pdf')   => 'pdf',
            str_contains($this->fichier_mime, 'word')  => 'word',
            str_contains($this->fichier_mime, 'excel') => 'excel',
            default                                    => 'file',
        };
    }

    public function getCategorieLabelAttribute(): string
    {
        return match ($this->categorie) {
            'photo_identite' => 'Photo d\'identité',
            'photo_cin'      => 'Photo CIN',
            'contrat'        => 'Contrat',
            'fiche_paie'     => 'Fiche de paie',
            'diplome'        => 'Diplôme',
            'attestation'    => 'Attestation',
            'autre'          => 'Autre',
            default          => 'Document',
        };
    }

    public function getCouleurAttribute(): string
    {
        return match ($this->categorie) {
            'photo_identite' => 'blue',
            'photo_cin'      => 'orange',
            'contrat'        => 'emerald',
            'fiche_paie'     => 'pink',
            'diplome'        => 'purple',
            'attestation'    => 'indigo',
            default          => 'slate',
        };
    }

    public function getMoisLabelAttribute(): ?string
    {
        if (!$this->mois) {
            return null;
        }

        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
        ];

        return $mois[$this->mois] ?? null;
    }
}