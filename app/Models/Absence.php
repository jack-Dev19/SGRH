<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'absences';
    protected $fillable = [ 
        'employe_id', 
        'date_debut', 
        'date_fin', 
        'nombre_jours', 
        'motif', 
        'description', 
        'justificatif', 
        'statut', 
        'valide_par', 
        'date_validation', 
        'commentaire_validation', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
            'nombre_jours' => 'decimal:2', 
            'date_validation' => 'datetime', 
        ]; 
    } 
    /** 
     * Employé concerné par l'absence. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    } 
    /** 
     * Utilisateur ayant validé ou refusé l'absence. 
     */ 
    public function validateur(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'valide_par' 
        ); 
    } 
}
