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
        Schema::create('departements', function (Blueprint $table) {
            // Identifiant UUID unique du département 
            $table->uuid('id')->primary(); 
            // Nom du département 
            $table->string('nom', 100)->unique(); 
            // Description du département 
            $table->text('description')->nullable(); 
            // Statut du département 
            $table->string('statut', 20)->default('actif'); 
            // created_at et updated_at 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departements');
    }
};
