<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class Role extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'roles';
    protected $fillable = ['nom', 'description'];

    // Relation : un rôle a plusieurs utilisateurs
    public function users() : HasMany {
        return $this->hasMany(User::class);
    }
}