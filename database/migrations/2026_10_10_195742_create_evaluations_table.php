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
        Schema::create('evaluations', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé évalué 
            $table->foreignUuid('employe_id') ->constrained('employes') ->restrictOnDelete(); 
            // Utilisateur ayant réalisé l'évaluation 
            $table->foreignUuid('evaluateur_id') ->constrained('users') ->restrictOnDelete(); 
            $table->foreignUuid('niveau_appreciation_id') ->nullable() ->constrained('niveaux_appreciation') ->nullOnDelete();
            // Informations générales 
            $table->string('titre', 150); 
            $table->string('type', 50); 
            // Exemple : S1 2025 
            $table->string('periode', 50); 
            // Période évaluée 
            $table->date('date_debut'); 
            $table->date('date_fin'); 
            // Date réelle de l'entretien 
            $table->date('date_evaluation')->nullable(); 
            // Score calculé sur 5 
            $table->decimal('score_global', 4, 2)->nullable(); 
            // Exemple : Excellent, Bien, Moyen... 
            $table->string('appreciation', 50)->nullable(); 
            // Commentaire général 
            $table->text('commentaire')->nullable(); 
            // Recommandation RH / manager 
            $table->text('recommandation')->nullable(); 
            // État de l'évaluation 
            $table->string('statut', 30)->default('brouillon'); 
            // Index 
            $table->index('employe_id'); 
            $table->index('evaluateur_id');
            $table->index('type'); 
            $table->index('periode'); 
            $table->index('statut'); 
            // Dates Laravel 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
