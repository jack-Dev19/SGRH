<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Departement extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'departements';
    protected $fillable = ['nom','description','statut','responsable_id'];
    
    protected function casts(): array {
        return ['responsable_id' => 'string',];
    }
    /** 
     * Les postes du département. 
     */ 
    public function postes(): HasMany 
    { 
        return $this->hasMany(Poste::class); 
    } 
    /** 
     * Les employés du département. 
     */ 
    public function employes(): HasMany 
    { 
        return $this->hasMany(Employe::class); 
    }
    /** 
     * Le responsable du département. 
     */ 
    public function responsable(): BelongsTo 
    { 
        return $this->belongsTo( Employe::class, 'responsable_id' );
    } 
    
    
}
=======
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
>>>>>>> 53d7c4557a96a51ce4c9fd11937087cee4018326
