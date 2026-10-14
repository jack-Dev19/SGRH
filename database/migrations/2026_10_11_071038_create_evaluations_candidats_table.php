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
        Schema::create('evaluations_candidats', function (Blueprint $table) {
             $table->uuid('id')->primary(); 
            // Candidature évaluée 
            $table->foreignUuid('candidature_id') 
                ->constrained('candidatures') 
                ->cascadeOnDelete(); 
            // Entretien concerné 
            $table->foreignUuid('entretien_id') 
                ->constrained('entretiens') 
                ->cascadeOnDelete(); 
            // Utilisateur ayant réalisé l'évaluation 
            $table->foreignUuid('evaluateur_id') 
                ->constrained('users') 
                ->restrictOnDelete(); 
            // Évaluation des compétences techniques 
            $table->decimal('competences_techniques', 5, 2) 
                ->nullable(); 
            // Évaluation de la communication 
            $table->decimal('communication', 5, 2) 
                ->nullable(); 
            // Évaluation de la motivation 
            $table->decimal('motivation', 5, 2) 
                ->nullable(); 
            // Évaluation de l'expérience 
            $table->decimal('experience', 5, 2) 
                ->nullable(); 
            // Note globale 
            $table->decimal('note_globale', 5, 2) 
                ->nullable(); 
            // Recommandation 
            $table->string('recommandation')->nullable(); 
            // Commentaire de l'évaluateur 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('candidature_id'); 
            $table->index('entretien_id'); 
            $table->index('evaluateur_id'); 
            $table->index('note_globale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations_candidats');
    }
};
