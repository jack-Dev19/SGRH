<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeFormation extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employes_formations';
     protected $fillable = [ 
        'employe_id', 
        'formation_id', 
        'date_inscription', 
        'date_debut', 
        'date_fin',
        'statut', 
        'note', 
        'resultat', 
        'certificat', 
        'fichier_certificat', 
        'commentaire', 
    ];

     protected function casts(): array 
    { 
        return [ 
            'date_inscription' => 'date', 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
            'note' => 'decimal:2', 
            'certificat' => 'boolean', 
        ]; 
    }
     /** 
     * Employé inscrit. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
     /** 
     * Formation concernée. 
     */ 
    public function formation(): BelongsTo 
    { 
        return $this->belongsTo(Formation::class); 
    }
}
