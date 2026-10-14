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
        Schema::create('jours_feries', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Nom du jour férié 
            $table->string('nom', 150); 
            // Date du jour férié 
            $table->date('date')->unique(); 
            // Description 
            $table->text('description')->nullable(); 
            // Indique si le jour est chômé 
            $table->boolean('est_chome')->default(true); 
            // Statut 
            $table->string('statut', 20)->default('actif'); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jours_feries');
    }
};
