<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneBulletinPaie extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'lignes_bulletins_paie';
    protected $fillable = [ 
        'bulletin_paie_id', 
        'type', 
        'libelle', 
        'quantite', 
        'taux', 
        'montant', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'quantite' => 'decimal:2', 
            'taux' => 'decimal:2', 
            'montant' => 'decimal:2', 
        ]; 
    }
    /** 
    * Bulletin auquel appartient cette ligne. 
    */ 
    public function bulletinPaie(): BelongsTo 
    { 
        return $this->belongsTo( 
            BulletinPaie::class, 
            'bulletin_paie_id' 
        ); 
    } 
}
