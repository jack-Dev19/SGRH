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
        Schema::create('niveaux_appreciation', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Nom du niveau 
            $table->string('nom', 100); 
            // Description 
            $table->text('description')->nullable(); 
            // Score minimum 
            $table->decimal('score_minimum', 4, 2); 
            // Score maximum 
            $table->decimal('score_maximum', 4, 2); 
            // Ordre d'affichage 
            $table->unsignedInteger('ordre')->default(0); 
            // Statut 
            $table->string('statut', 20)->default('actif'); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('statut'); 
            $table->index('ordre'); 
            // Évite deux niveaux portant exactement le même nom 
            $table->unique('nom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveaux_appreciation');
    }
};
