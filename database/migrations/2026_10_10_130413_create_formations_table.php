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
        Schema::create('formations', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Nom de la formation 
            $table->string('nom', 150); 
            // Description 
            $table->text('description')->nullable(); 
            // Organisme 
            $table->string('organisme', 150)->nullable(); 
            // Formateur 
            $table->string('formateur', 150)->nullable(); 
            // Lieu 
            $table->string('lieu', 150)->nullable(); 
            // Type de formation 
            $table->string('type', 50)->nullable(); 
            // Durée en heures 
            $table->decimal('duree_heures', 6, 2)->nullable(); 
            // Dates 
            $table->date('date_debut')->nullable(); 
            $table->date('date_fin')->nullable(); 
            // Nombre maximum de participants 
            $table->unsignedInteger('capacite')->nullable(); 
            // Coût de la formation 
            $table->decimal('cout', 12, 2)->nullable(); 
            // Statut 
            $table->string('statut', 30)->default('planifiee'); 
            // Dates Laravel 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
