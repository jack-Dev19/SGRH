<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'notifications'; 
    protected $fillable = [ 
        'user_id', 
        'titre', 
        'message', 
        'type', 
        'lue', 
        'date_lecture', 
        'url', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'lue' => 'boolean', 
            'date_lecture' => 'datetime', 
        ]; 
    } 
    /** 
    * Utilisateur destinataire de la notification. 
    */
    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
