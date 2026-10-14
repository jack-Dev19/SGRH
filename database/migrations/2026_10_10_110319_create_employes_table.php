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
        Schema::create('employes', function (Blueprint $table) {
             // Identifiant UUID de l'employé 
            $table->uuid('id')->primary(); 
            // Compte utilisateur associé à l'employé 
            $table->foreignUuid('user_id') 
                ->nullable() 
                ->unique() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Département de l'employé 
            $table->foreignUuid('departement_id') 
                ->constrained('departements') 
                ->restrictOnDelete(); 
            // Poste occupé par l'employé 
            $table->foreignUuid('poste_id') 
                ->constrained('postes') 
                ->restrictOnDelete(); 
            // Matricule professionnel 
            $table->string('matricule', 30)->unique(); 
            // Informations personnelles 
            $table->string('nom', 100); 
            $table->string('prenom', 100); 
            $table->string('sexe', 20)->nullable(); 
            $table->date('date_naissance')->nullable(); 
            // Coordonnées 
            $table->string('telephone', 30)->nullable(); 
            $table->string('email', 255)->nullable(); 
            $table->text('adresse')->nullable(); 
            // Informations professionnelles 
            $table->date('date_embauche'); 
            $table->date('date_sortie')->nullable(); 
            // Statut de l'employé 
            $table->string('statut', 30)->default('actif'); 
            // Photo 
            $table->string('photo', 255)->nullable(); 
            // created_at et updated_at 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
