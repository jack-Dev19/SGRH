<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poste extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'postes';
    protected $fillable = ['departement_id','nom','description','niveau','salaire_min',
    'salaire_max','statut',];

    protected function casts(): array 
    { 
        return [ 
            'salaire_min' => 'decimal:2', 
            'salaire_max' => 'decimal:2', 
        ]; 
    } 
    /** 
     * Le département auquel appartient le poste. 
     */ 
    public function departement(): BelongsTo 
    { 
        return $this->belongsTo(Departement::class); 
    } 
     /** 
     * Les employés occupant ce poste. 
     */ 
    public function employes(): HasMany 
    { 
        return $this->hasMany(Employe::class); 
    }
    /** 
     * Les offres d'emploi associées à ce poste. 
     */ 
    public function offresEmploi(): HasMany 
    { 
        return $this->hasMany(OffreEmploi::class); 
    }
}
