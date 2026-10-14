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
        Schema::create('documents_employes', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') ->constrained('employes') ->restrictOnDelete(); 
            // Type du document 
            $table->string('type_document', 100); 
            // Nom du document 
            $table->string('nom', 255); 
            // Chemin du fichier 
            $table->string('fichier', 255); 
            // Nom original du fichier 
            $table->string('nom_original', 255)->nullable(); 
            // Extension 
            $table->string('extension', 20)->nullable(); 
            // Type MIME 
            $table->string('mime_type', 100)->nullable(); 
            // Taille du fichier en octets 
            $table->unsignedBigInteger('taille')->nullable(); 
            // Date du document 
            $table->date('date_document')->nullable(); 
            // Date d'expiration 
            $table->date('date_expiration')->nullable(); 
            // Description 
            $table->text('description')->nullable(); 
            // Statut 
            $table->string('statut', 20)->default('actif'); 
            // Utilisateur ayant téléversé le document 
            $table->foreignUuid('uploaded_by') 
                ->nullable() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Index 
            $table->index(['employe_id', 'type_document']); 
            $table->index('date_expiration'); 
            $table->index('statut'); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_employes');
    }
};
