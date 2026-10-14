<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class OffreEmploi extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'offres_emploi';
    protected $fillable = [ 
        'poste_id', 
        'titre', 
        'description', 
        'profil_recherche', 
        'competences_requises', 
        'type_contrat', 
        'salaire_min', 
        'salaire_max', 
        'date_publication', 
        'date_expiration', 
        'statut', 
    ];
    protected function casts(): array
    {
        return [
            'salaire_min' => 'decimal:2',
            'salaire_max' => 'decimal:2', 
            'date_publication' => 'date', 
            'date_expiration' => 'date', 
        ];
    }
    /** 
    * Poste concerné par l'offre. 
    */ 
    public function poste(): BelongsTo 
    { 
        return $this->belongsTo(Poste::class); 
    }
    /** 
    * Candidatures reçues pour cette offre. 
    */ 
    public function candidatures(): HasMany 
    { 
        return $this->hasMany(Candidature::class); 
    }
}
