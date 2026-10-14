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
        Schema::create('candidats', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->string('nom', 100); 
            $table->string('prenom', 100); 
            $table->string('email', 255); 
            $table->string('telephone', 30)->nullable(); 
            $table->text('adresse')->nullable(); 
            $table->date('date_naissance')->nullable(); 
            $table->string('sexe', 20)->nullable(); 
            $table->string('ville', 100)->nullable();
            $table->string('pays', 100)->nullable();
            $table->string('photo', 255)->nullable();
            $table->text('profil')->nullable(); 
            $table->string('linkedin', 255)->nullable(); 
            $table->string('portfolio', 255)->nullable(); 
            $table->string('statut', 20)->default('actif'); 
            $table->text('commentaire')->nullable(); 
            $table->timestamps(); 
            $table->index('nom'); 
            $table->index('email'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
