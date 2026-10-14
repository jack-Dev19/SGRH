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
        Schema::create('employes_competences', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé 
            $table->foreignUuid('employe_id') ->constrained('employes') 
                ->restrictOnDelete(); 
            // Compétence 
            $table->foreignUuid('competence_id') 
                ->constrained('competences') 
                ->restrictOnDelete(); 
            // Niveau de compétence 
            $table->string('niveau', 30)->default('debutant'); 
            // Score d'évaluation sur 100 
            $table->decimal('score', 5, 2)->nullable(); 
            // Nombre d'années d'expérience 
            $table->decimal('annees_experience', 5, 2)->nullable(); 
 
            // Date de l'évaluation 
            $table->date('date_evaluation')->nullable(); 
            // Utilisateur ayant effectué l'évaluation 
            $table->foreignUuid('evalue_par') 
                ->nullable() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Commentaire de l'évaluation 
            $table->text('commentaire')->nullable(); 
            // Statut 
            $table->string('statut', 20)->default('actif'); 
            // Empêcher les doublons 
            $table->unique(['employe_id', 'competence_id']); 
            // Index 
            $table->index('niveau'); 
            $table->index('statut'); 
            $table->index('date_evaluation'); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes_competences');
    }
};
