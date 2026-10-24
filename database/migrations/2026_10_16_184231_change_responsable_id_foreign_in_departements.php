<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ⭐ Étape 1 : Nettoyer les responsable_id qui pointent vers employes
        // (avant de changer la FK, sinon la migration échoue)
        DB::table('departements')->update(['responsable_id' => null]);

        // ⭐ Étape 2 : Modifier la contrainte FK
        Schema::table('departements', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte (vers employes)
            $table->dropForeign(['responsable_id']);

            // Recréer la contrainte vers users
            $table->foreign('responsable_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);

            $table->foreign('responsable_id')
                  ->references('id')
                  ->on('employes')
                  ->onDelete('set null');
        });
    }
};