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
        Schema::create('types_conges', function (Blueprint $table) {
            // Identifiant UUID du type de congé 
            $table->uuid('id')->primary(); 
            // Nom du type de congé 
            $table->string('nom', 100)->unique(); 
            // Code interne du type de congé 
            $table->string('code', 30)->unique(); 
            // Description 
            $table->text('description')->nullable(); 
            // Durée maximale autorisée en jours 
            $table->unsignedInteger('duree_max')->nullable(); 
            // Indique si un justificatif est obligatoire 
            $table->boolean('necessite_justificatif')->default(false); 
            // Statut du type de congé 
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
        Schema::dropIfExists('types_conges');
    }
};
