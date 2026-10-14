<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeConge extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'types_conges';
    protected $fillable = [ 
        'nom', 
        'code', 
        'description', 
        'duree_max', 
        'necessite_justificatif', 
        'statut', 
    ];
    protected function casts(): array 
    { 
        return [ 
        'duree_max' => 'integer', 
        'necessite_justificatif' => 'boolean', 
        ]; 
    } 
    /** 
    * Demandes de congés utilisant ce type. 
    */ 
    public function demandesConges(): HasMany 
    { 
        return $this->hasMany(DemandeConge::class); 
    } 
}
