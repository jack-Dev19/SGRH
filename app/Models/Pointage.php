<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pointage extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'pointages';
    protected $fillable = [ 
        'employe_id', 
        'date_heure', 
        'type', 
        'source', 
        'reference', 
        'terminal', 
        'donnees', 
        'commentaire', 
    ];
    protected function casts(): array 
    { 
        return [ 
            'date_heure' => 'datetime', 
            'donnees' => 'array', 
        ]; 
    } 
     /** 
     * Employé concerné par le pointage. 
     */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
}
