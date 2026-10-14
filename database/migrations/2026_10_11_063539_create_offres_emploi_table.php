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
        Schema::create('offres_emploi', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Poste recherché 
            $table->foreignUuid('poste_id') 
                ->constrained('postes') 
                ->restrictOnDelete(); 
            // Titre de l'offre 
            $table->string('titre'); 
            // Description de l'offre 
            $table->text('description'); 
            // Profil recherché 
            $table->text('profil_recherche'); 
            // Compétences requises 
            $table->text('competences_requises')->nullable(); 
            // Type de contrat 
            $table->string('type_contrat'); 
            // Fourchette salariale 
            $table->decimal('salaire_min', 15, 2)->nullable(); 
            $table->decimal('salaire_max', 15, 2)->nullable(); 
            // Dates 
            $table->date('date_publication'); 
            $table->date('date_expiration')->nullable(); 
            // Statut 
            $table->string('statut')->default('brouillon'); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('poste_id'); 
            $table->index('type_contrat'); 
            $table->index('date_publication'); 
            $table->index('date_expiration'); 
            $table->index('statut'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offres_emploi');
    }
};
