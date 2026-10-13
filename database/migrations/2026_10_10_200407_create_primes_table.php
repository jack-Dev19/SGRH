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
        Schema::create('primes', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->cascadeOnDelete(); 
            // Type de prime 
            $table->string('type_prime'); 
            // Montant de la prime 
            $table->decimal('montant', 15, 2); 
            // Date d'attribution 
            $table->date('date_prime'); 
            // Statut 
            $table->string('statut')->default('accordee'); 
            // Motif 
            $table->string('motif')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('employe_id'); 
            $table->index('type_prime'); 
            $table->index('date_prime'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('primes');
    }
};
