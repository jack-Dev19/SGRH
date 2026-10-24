<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absences', function (Blueprint $table) {
            // Origine de l'absence : 'auto' ou 'manuelle'
            $table->string('origine', 20)->default('manuelle')->after('statut');

            // Justificatif uploadé par RH (chemin fichier)
            $table->string('justificatif_path', 255)->nullable()->after('origine');
        });
    }

    public function down(): void
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->dropColumn(['origine', 'justificatif_path']);
        });
    }
};