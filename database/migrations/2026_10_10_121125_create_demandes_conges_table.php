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
        Schema::create('demandes_conges', function (Blueprint $table) {
             // Identifiant UUID de la demande 
            $table->uuid('id')->primary(); 
            // Employé qui effectue la demande 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->restrictOnDelete(); 
            // Type de congé demandé 
            $table->foreignUuid('type_conge_id') 
                ->constrained('types_conges') 
                ->restrictOnDelete(); 
            // Période du congé 
            $table->date('date_debut'); 
            $table->date('date_fin'); 
            // Nombre de jours demandés 
            $table->decimal('nombre_jours', 5, 2); 
            // Motif de la demande 
            $table->text('motif')->nullable(); 
            // Chemin du justificatif 
            $table->string('justificatif', 255)->nullable(); 
            // Statut de la demande 
            $table->string('statut', 20)->default('en_attente'); 
            // Commentaire du validateur 
            $table->text('commentaire_validation')->nullable(); 
            // Utilisateur ayant traité la demande 
            $table->foreignUuid('valide_par') 
                ->nullable() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Date du traitement 
            $table->timestamp('date_validation')->nullable(); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_conges');
    }
};
