<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueAction extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'historiques_actions'; 
    protected $fillable = [ 
        'user_id', 
        'action', 
        'module', 
        'description', 
        'type_cible', 
        'cible_id', 
        'ancienne_valeur', 
        'nouvelle_valeur', 
        'adresse_ip', 
        'user_agent', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'ancienne_valeur' => 'array', 
            'nouvelle_valeur' => 'array', 
        ]; 
    } 
    /** 
    * Utilisateur ayant effectué l'action. 
    */ 
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }
}
