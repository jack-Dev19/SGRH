<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeConge extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'demandes_conges';
    protected $fillable = [ 
        'employe_id', 
        'type_conge_id', 
        'date_debut', 
        'date_fin', 
        'nombre_jours', 
        'motif', 
        'justificatif', 
        'statut', 
        'commentaire_validation', 
        'valide_par', 
        'date_validation', 
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
     * Employé ayant effectué la demande. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    } 
     /** 
     * Type de congé demandé. 
     */ 
    public function typeConge(): BelongsTo 
    { 
        return $this->belongsTo(TypeConge::class); 
    } 
    /** 
     * Utilisateur ayant validé ou refusé la demande. 
     */ 
    public function validateur(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'valide_par' 
        ); 
    }
}
