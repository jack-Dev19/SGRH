<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('horaires_travail', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Nom de l'horaire 
            $table->string('nom', 100)->unique(); 
            // Description 
            $table->text('description')->nullable(); 
            // Horaires principaux 
            $table->time('heure_debut')->nullable(); 
            $table->time('heure_fin')->nullable(); 
            // Pause 
            $table->time('debut_pause')->nullable(); 
            $table->time('fin_pause')->nullable(); 
            // Durée de pause en heures 
            $table->decimal('duree_pause', 4, 2)->nullable(); 
            // Tolérance de retard en minutes 
            $table->unsignedInteger('tolerance_retard')->default(0); 
            // Nombre d'heures prévues 
            $table->decimal('heures_prevues', 5, 2)->nullable(); 
            // Statut 
            $table->string('statut', 20)->default('actif'); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaires_travail');
    }
};
