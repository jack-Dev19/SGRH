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
        Schema::create('rapports', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Utilisateur ayant généré le rapport 
            $table->foreignUuid('user_id') 
                ->constrained('users') 
                ->restrictOnDelete(); 
            // Titre du rapport 
            $table->string('titre'); 
            // Type du rapport 
            $table->string('type_rapport'); 
            // Période concernée 
            $table->date('periode_debut')->nullable(); 
            $table->date('periode_fin')->nullable(); 
            // Format du rapport 
            $table->string('format')->default('pdf'); 
            // Chemin du fichier 
            $table->string('chemin')->nullable(); 
            // Description 
            $table->text('description')->nullable(); 
            // Statut 
            $table->string('statut')->default('genere'); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('user_id'); 
            $table->index('type_rapport'); 
            $table->index('periode_debut'); 
            $table->index('periode_fin'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
