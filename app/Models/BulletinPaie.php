<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulletinPaie extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'bulletins_paie';
     protected $fillable = [ 
        'employe_id', 
        'periode', 
        'annee', 
        'salaire_base', 
        'total_primes', 
        'total_heures_supplementaires', 
        'total_retenues', 
        'salaire_brut', 
        'salaire_net', 
        'statut', 
        'date_generation', 
        'commentaire', 
    ];

     protected function casts(): array 
    { 
        return [ 
            'annee' => 'integer', 
            'salaire_base' => 'decimal:2', 
            'total_primes' => 'decimal:2', 
            'total_heures_supplementaires' => 'decimal:2', 
            'total_retenues' => 'decimal:2', 
            'salaire_brut' => 'decimal:2', 
            'salaire_net' => 'decimal:2', 
            'date_generation' => 'date', 
        ]; 
    }
     /** 
     * Employé concerné par le bulletin. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
     /** 
     * Lignes détaillées du bulletin. 
     */ 
    public function lignes(): HasMany 
    { 
        return $this->hasMany(LigneBulletinPaie::class); 
    }
}
