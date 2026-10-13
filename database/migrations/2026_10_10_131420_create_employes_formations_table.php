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
        Schema::create('employes_formations', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé 
            $table->foreignUuid('employe_id') ->constrained('employes') ->restrictOnDelete(); 
            // Formation 
            $table->foreignUuid('formation_id') ->constrained('formations') ->restrictOnDelete(); 
            // Date d'inscription 
            $table->date('date_inscription'); 
            // Période réelle de participation 
            $table->date('date_debut')->nullable(); 
            $table->date('date_fin')->nullable(); 
            // Statut de la participation 
            $table->string('statut', 30)->default('inscrite'); 
            // Note obtenue 
            $table->decimal('note', 5, 2)->nullable(); 
            // Résultat 
            $table->string('resultat', 50)->nullable(); 
            // Certificat obtenu 
            $table->boolean('certificat')->default(false); 
            // Chemin du certificat 
            $table->string('fichier_certificat', 255)->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Empêcher une double inscription 
            $table->unique(['employe_id', 'formation_id']); 
            // Index 
            $table->index('statut'); 
            $table->index('date_inscription'); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes_formations');
    }
};
