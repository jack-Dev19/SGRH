<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rapport extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'rapports'; 
    protected $fillable = [ 
        'user_id', 
        'titre', 
        'type_rapport', 
        'periode_debut', 
        'periode_fin', 
        'format', 
        'chemin', 
        'description', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'periode_debut' => 'date', 
            'periode_fin' => 'date', 
        ]; 
    }
     /** 
     * Utilisateur ayant généré le rapport. 
     */ 
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
}
