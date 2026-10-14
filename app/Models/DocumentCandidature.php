<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentCandidature extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'documents_candidatures';
    protected $fillable = [ 
        'candidature_id', 
        'type_document', 
        'nom_fichier', 
        'chemin', 
        'extension', 
        'taille', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'taille' => 'integer', 
        ]; 
    }
    /** 
    * Candidature concernée. 
    */ 
    public function candidature(): BelongsTo 
    { 
        return $this->belongsTo(Candidature::class); 
    } 
}
