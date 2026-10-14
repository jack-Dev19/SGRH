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
        Schema::create('entretiens', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Candidature concernée 
            $table->foreignUuid('candidature_id') 
                ->constrained('candidatures') 
                ->cascadeOnDelete(); 
            // Utilisateur chargé de l'entretien 
            $table->foreignUuid('evaluateur_id') 
                ->nullable() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Date de l'entretien 
            $table->date('date_entretien'); 
            // Heure de l'entretien 
            $table->time('heure_entretien')->nullable(); 
            // Type d'entretien 
            $table->string('type_entretien'); 
            // Lieu 
            $table->string('lieu')->nullable(); 
            // Statut de l'entretien 
            $table->string('statut')->default('planifie'); 
            // Note 
            $table->decimal('note', 5, 2)->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Résultat 
            $table->string('resultat')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('candidature_id'); 
            $table->index('evaluateur_id'); 
            $table->index('date_entretien'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};
