<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoraireTravail extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'horaires_travail';
    protected $fillable = [ 
        'nom', 
        'description', 
        'heure_debut', 
        'heure_fin', 
        'debut_pause', 
        'fin_pause', 
        'duree_pause', 
        'tolerance_retard', 
        'heures_prevues', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'duree_pause' => 'decimal:2', 
            'tolerance_retard' => 'integer', 
            'heures_prevues' => 'decimal:2', 
        ]; 
    } 
     /** 
     * Jours associés à cet horaire. 
     */ 
    public function horairesJours(): HasMany 
    { 
        return $this->hasMany(HoraireJour::class); 
    }
     /** 
     * Affectations de cet horaire aux employés. 
     */ 
    public function affectationsHoraires(): HasMany 
    { 
        return $this->hasMany(AffectationHoraire::class); 
    } 
}
