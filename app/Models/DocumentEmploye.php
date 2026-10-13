<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentEmploye extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'documents_employes';
    protected $fillable = [ 
        'employe_id', 
        'type_document', 
        'nom', 
        'fichier', 
        'nom_original', 
        'extension', 
        'mime_type', 
        'taille', 
        'date_document', 
        'date_expiration', 
        'description', 
        'statut', 
        'uploaded_by', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'taille' => 'integer', 
            'date_document' => 'date', 
            'date_expiration' => 'date', 
        ]; 
    }
    /** 
    * Employé propriétaire du document. 
    */ 
    public function employe(): BelongsTo 
    { 
        return $this->belongsTo(Employe::class); 
    }
    /** 
     * Utilisateur ayant téléversé le document. 
     */ 
    public function uploader(): BelongsTo 
    { 
        return $this->belongsTo( 
            User::class, 
            'uploaded_by' 
        ); 
    }
}
