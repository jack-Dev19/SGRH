<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'jours_feries';
    protected $fillable = [ 
        'nom', 
        'date', 
        'description', 
        'est_chome', 
        'statut', 
    ];

    protected function casts(): array 
    { 
        return [ 
            'date' => 'date', 
            'est_chome' => 'boolean', 
        ]; 
    } 
}
