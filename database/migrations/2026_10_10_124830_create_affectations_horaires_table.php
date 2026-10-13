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
        Schema::create('affectations_horaires', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->restrictOnDelete(); 
            // Horaire attribué 
            $table->foreignUuid('horaire_id') 
                ->constrained('horaires_travail') 
                ->restrictOnDelete(); 
            // Période de l'affectation 
            $table->date('date_debut'); 
            $table->date('date_fin')->nullable(); 
            // Statut 
            $table->string('statut', 20)->default('active'); 
            // Motif du changement 
            $table->text('motif')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Index 
            $table->index(['employe_id', 'date_debut']); 
            $table->index(['horaire_id', 'date_debut']); 
            // Dates Laravel 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations_horaires');
    }
};
