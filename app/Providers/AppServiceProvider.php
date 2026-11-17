<?php

namespace App\Providers;

use App\Models\Parametre;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ==========================================
        // 1. GESTION DU FUSEAU HORAIRE
        // ==========================================

        $fuseauHoraire = Parametre::obtenir(
            'general',
            'fuseau_horaire',
            config('app.timezone')
        );

        // Vérifier et appliquer le fuseau horaire.
        if (in_array($fuseauHoraire, timezone_identifiers_list(), true)) {
            config(['app.timezone' => $fuseauHoraire]);

            date_default_timezone_set($fuseauHoraire);
        }

        // ==========================================
        // 2. GESTION DE LA LANGUE
        // ==========================================

        $langue = Parametre::obtenir(
            'general',
            'langue',
            config('app.locale')
        );

        // Vérifier que la langue est autorisée.
        if (in_array($langue, ['fr', 'mg', 'en'], true)) {
            config(['app.locale' => $langue]);
        }
    }

}
