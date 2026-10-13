<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidat extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'candidats';
    protected $fillable = [ 
        'nom', 
        'prenom', 
        'email', 
        'telephone', 
        'adresse', 
        'date_naissance', 
        'sexe', 
        'ville', 
        'pays', 
        'photo', 
        'statut', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_naissance' => 'date', 
        ]; 
    }
     /** 
     * Candidatures du candidat. 
     */ 
    public function candidatures(): HasMany 
    { 
        return $this->hasMany(Candidature::class); 
    }
}
