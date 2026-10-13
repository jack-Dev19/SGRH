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
        Schema::create('presences', function (Blueprint $table) {
            // Identifiant UUID de la présence 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->restrictOnDelete(); 
            // Date de la présence 
            $table->date('date'); 
            // Heures d'entrée et de sortie 
            $table->time('heure_entree')->nullable(); 
            $table->time('heure_sortie')->nullable(); 
            // Statut de présence 
            $table->string('statut', 30)->default('present'); 
            // Nombre de minutes de retard 
            $table->unsignedInteger('retard_minutes')->default(0); 
            // Nombre d'heures travaillées 
            $table->decimal('heures_travaillees', 5, 2)->nullable(); 
            // Source du pointage 
            $table->string('source', 20)->default('manuel'); 
            // Référence provenant éventuellement 
            // d'un appareil ou d'une API 
            $table->string('reference_pointage', 100)->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Empêcher plusieurs présences 
            // pour le même employé le même jour 
            $table->unique(['employe_id', 'date']); 
            // Dates Laravel 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
