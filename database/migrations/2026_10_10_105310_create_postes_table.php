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
        Schema::create('postes', function (Blueprint $table) {
             // Identifiant UUID du poste 
            $table->uuid('id')->primary(); 
            // Département auquel appartient le poste 
            $table->foreignUuid('departement_id') 
                ->constrained('departements') 
                ->restrictOnDelete(); 
            // Nom du poste 
            $table->string('nom', 100); 
            // Description du poste 
            $table->text('description')->nullable(); 
            // Niveau hiérarchique 
            $table->string('niveau', 30)->nullable(); 
            // Fourchette salariale 
            $table->decimal('salaire_min', 12, 2)->nullable(); 
            $table->decimal('salaire_max', 12, 2)->nullable(); 
            // Statut du poste 
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
        Schema::dropIfExists('postes');
    }
};
