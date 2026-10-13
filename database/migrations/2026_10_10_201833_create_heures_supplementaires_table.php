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
        Schema::create('heures_supplementaires', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->cascadeOnDelete(); 
            // Date des heures supplémentaires 
            $table->date('date'); 
            // Nombre d'heures supplémentaires 
            $table->decimal('nombre_heures', 5, 2); 
            // Taux de majoration en pourcentage 
            $table->decimal('taux', 5, 2); 
            // Montant correspondant aux heures supplémentaires 
            $table->decimal('montant', 15, 2); 
            // Statut 
            $table->string('statut')->default('en_attente'); 
            // Motif 
            $table->string('motif')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('employe_id'); 
            $table->index('date'); 
            $table->index('statut'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heures_supplementaires');
    }
};
