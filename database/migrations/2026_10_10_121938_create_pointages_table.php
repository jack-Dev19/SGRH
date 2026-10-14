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
        Schema::create('pointages', function (Blueprint $table) {
            // Identifiant UUID du pointage 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') ->constrained('employes') ->restrictOnDelete(); 
            // Date et heure exacte du pointage 
            $table->dateTime('date_heure'); 
            // Type du pointage 
            $table->string('type', 30); 
            // Source du pointage 
            $table->string('source', 20)->default('manuel'); 
            // Référence externe 
            $table->string('reference', 100)->nullable(); 
            // Terminal utilisé 
            $table->string('terminal', 100)->nullable(); 
            // Informations supplémentaires 
            $table->json('donnees')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Index pour accélérer les recherches 
            $table->index(['employe_id', 'date_heure']); 
            $table->index('source'); 
            $table->index('type'); 
            // Dates Laravel 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
