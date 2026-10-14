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
        Schema::create('contrats', function (Blueprint $table) {
             // Identifiant UUID du contrat 
            $table->uuid('id')->primary(); 
            // Employé concerné par le contrat 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->restrictOnDelete(); 
            // Numéro unique du contrat 
            $table->string('numero', 50)->unique(); 
            // Type de contrat 
            $table->string('type', 30); 
            // Dates du contrat 
            $table->date('date_debut'); 
            $table->date('date_fin')->nullable(); 
            // Informations salariales 
            $table->decimal('salaire', 12, 2); 
            $table->string('devise', 10)->default('MGA'); 
            // Période d'essai 
            $table->unsignedInteger('periode_essai')->nullable(); 
            $table->string('unite_periode_essai', 20)->nullable(); 
            // Statut du contrat 
            $table->string('statut', 20)->default('actif'); 
            // Informations complémentaires 
            $table->text('description')->nullable(); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
