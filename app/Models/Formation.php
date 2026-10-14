<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'formations';
     protected $fillable = [ 
        'nom', 
        'description', 
        'organisme', 
        'formateur', 
        'lieu', 
        'type', 
        'duree_heures', 
        'date_debut', 
        'date_fin', 
        'capacite', 
        'cout', 
        'statut', 
    ];

     protected function casts(): array 
    { 
        return [ 
            'duree_heures' => 'decimal:2', 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
            'capacite' => 'integer', 
            'cout' => 'decimal:2', 
        ]; 
    }
     /** 
     * Inscriptions détaillées des employés. 
     */ 
    public function employesFormations(): HasMany 
    { 
        return $this->hasMany(EmployeFormation::class); 
    }
     /** 
     * Employés inscrits à la formation. 
     */ 
    public function employes(): BelongsToMany 
    { 
        return $this->belongsToMany( 
            Employe::class, 
            'employes_formations', 
            'formation_id', 
            'employe_id' 
        )->withPivot([ 
            'id', 
            'date_inscription', 
            'date_debut', 
            'date_fin', 
            'statut', 
            'note', 
            'resultat', 
            'certificat', 
            'fichier_certificat', 
            'commentaire', 
        ])->withTimestamps(); 
    }
}
