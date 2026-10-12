<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'date_debut', 'date_fin', 'motif', 'statut'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}