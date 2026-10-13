<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'matricule', 'nom', 'prenom', 'sexe', 'email',
        'telephone', 'salaire_base', 'date_embauche',
        'departement_id', 'actif'
    ];
    
    protected $casts = [
        'date_embauche' => 'date',
        'actif' => 'boolean',
    ];

    // Un employé appartient à un département
    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    // Un employé a plusieurs congés
    public function conges()
    {
        return $this->hasMany(Conge::class);
    }
}