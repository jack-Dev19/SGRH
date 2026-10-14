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
        Schema::create('documents_candidatures', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Candidature concernée 
            $table->foreignUuid('candidature_id') 
                ->constrained('candidatures') 
                ->cascadeOnDelete(); 
            // Type du document 
            $table->string('type_document'); 
            // Nom original du fichier 
            $table->string('nom_fichier'); 
            // Chemin de stockage 
            $table->string('chemin'); 
            // Extension du fichier 
            $table->string('extension')->nullable(); 
            // Taille du fichier en octets 
            $table->unsignedBigInteger('taille')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('candidature_id'); 
            $table->index('type_document'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_candidatures');
    }
};
