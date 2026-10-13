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
        Schema::create('salaires', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->cascadeOnDelete(); 
            // Salaire de base 
            $table->decimal('salaire_base', 15, 2); 
            // Date de début du salaire 
            $table->date('date_debut'); 
            // Date de fin du salaire 
            $table->date('date_fin')->nullable(); 
            // Motif du changement de salaire 
            $table->string('motif')->nullable(); 
            // Statut du salaire 
            $table->string('statut')->default('actif'); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps();
            // Index 
            $table->index('employe_id'); 
            $table->index('date_debut'); 
            $table->index('statut'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaires');
    }
};
