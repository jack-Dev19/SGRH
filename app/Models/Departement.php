<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = ['nom', 'couleur'];

    // Un département a plusieurs employés
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}