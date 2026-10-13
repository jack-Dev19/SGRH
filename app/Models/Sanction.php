<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sanction extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'sanctions';
    protected $fillable = [ 
        'employe_id', 
        'user_id', 
        'type_sanction', 
        'motif', 
        'date_sanction', 
        'date_debut', 
        'date_fin', 
        'description', 
        'decision', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date_sanction' => 'date', 
            'date_debut' => 'date', 
            'date_fin' => 'date', 
        ]; 
    }
     /** 
     * Employé ayant reçu la sanction. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
     /** 
     * Utilisateur ayant enregistré ou décidé la sanction. 
     */ 
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
}
