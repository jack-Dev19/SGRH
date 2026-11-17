<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory, HasUuids;

    /**
     * Table associée au modèle.
     */
    protected $table = 'parametres';

    /**
     * Champs autorisés à être remplis.
     */
    protected $fillable = [
        'groupe',
        'cle',
        'valeur',
    ];

    /**
     * Récupérer la valeur d'un paramètre.
     */
    public static function obtenir(string $groupe, string $cle, mixed $valeurParDefaut = null): mixed
    {
        $parametre = static::where('groupe', $groupe)
            ->where('cle', $cle)
            ->first();

        return $parametre?->valeur ?? $valeurParDefaut;
    }

    /**
     * Créer ou modifier un paramètre.
     */
    public static function definir(string $groupe, string $cle, mixed $valeur): self
    {
        return static::updateOrCreate(
            [
                'groupe' => $groupe,
                'cle' => $cle,
            ],
            [
                'valeur' => is_array($valeur)
                    ? json_encode($valeur, JSON_UNESCAPED_UNICODE)
                    : (string) $valeur,
            ]
        );
    }
}
