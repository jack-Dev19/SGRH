<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrat extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'contrats';
    protected $fillable = [ 
        'employe_id', 
        'numero', 
        'type', 
        'date_debut', 
        'date_fin', 
        'salaire', 
        'devise', 
        'periode_essai', 
        'unite_periode_essai', 
        'statut', 
        'description', 
    ]; 

    protected function casts(): array 
    { 
        return [ 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
            'salaire' => 'decimal:2', 
            'periode_essai' => 'integer', 
        ]; 
    } 
    /** 
     * Le contrat appartient à un employé. 
     */ 
    public function employe(): BelongsTo
    { 
        return $this->belongsTo(Employe::class); 
    } 
}
