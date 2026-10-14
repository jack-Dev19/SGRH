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
        Schema::create('absences', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') ->constrained('employes') ->restrictOnDelete(); 
            // Période de l'absence 
            $table->date('date_debut'); 
            $table->date('date_fin'); 
            // Nombre de jours 
            $table->decimal('nombre_jours', 5, 2); 
            // Motif 
            $table->string('motif', 150)->nullable(); 
            // Description détaillée 
            $table->text('description')->nullable(); 
            // Chemin du justificatif 
            $table->string('justificatif', 255)->nullable(); 
            // Statut 
            $table->string('statut', 30)->default('en_attente'); 
            // Utilisateur ayant traité l'absence 
            $table->foreignUuid('valide_par') ->nullable() ->constrained('users') ->nullOnDelete(); 
            // Date de validation 
            $table->timestamp('date_validation')->nullable(); 
            // Commentaire du validateur 
            $table->text('commentaire_validation')->nullable(); 
            // Index 
            $table->index(['employe_id', 'date_debut']); 
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
        Schema::dropIfExists('absences');
    }
};
