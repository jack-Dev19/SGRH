<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retenue extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'retenues';
    protected $fillable = [ 
        'employe_id', 
        'type_retenue', 
        'montant', 
        'date_retenue', 
        'statut', 
        'motif', 
        'commentaire', 
    ];
    protected function casts(): array 
    { 
        return [ 
            'montant' => 'decimal:2', 
            'date_retenue' => 'date', 
        ]; 
    }
    /** 
    * Employé concerné par la retenue. 
    */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
