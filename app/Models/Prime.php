<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prime extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'primes';
     protected $fillable = [ 
        'employe_id', 
        'type_prime', 
        'montant', 
        'date_prime', 
        'statut', 
        'motif', 
        'commentaire', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'montant' => 'decimal:2', 
            'date_prime' => 'date', 
        ]; 
    }
     /** 
     * Employé bénéficiaire de la prime. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
