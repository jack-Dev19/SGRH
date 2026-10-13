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
        Schema::create('competences', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Nom de la compétence 
            $table->string('nom', 150)->unique(); 
            // Catégorie 
            $table->string('categorie', 100)->nullable(); 
            // Description 
            $table->text('description')->nullable(); 
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
        Schema::dropIfExists('competences');
    }
};
