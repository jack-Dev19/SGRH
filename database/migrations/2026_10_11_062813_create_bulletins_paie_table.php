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
        Schema::create('bulletins_paie', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->cascadeOnDelete(); 
            // Période de paie 
            $table->string('periode'); 
            // Année 
            $table->unsignedSmallInteger('annee'); 
            // Salaire de base 
            $table->decimal('salaire_base', 15, 2); 
            // Total des primes 
            $table->decimal('total_primes', 15, 2)->default(0); 
            // Total des heures supplémentaires 
            $table->decimal('total_heures_supplementaires', 15, 2) 
                ->default(0); 
            // Total des retenues 
            $table->decimal('total_retenues', 15, 2)->default(0); 
            // Salaire brut 
            $table->decimal('salaire_brut', 15, 2); 
            // Salaire net 
            $table->decimal('salaire_net', 15, 2); 
            // Statut du bulletin 
            $table->string('statut')->default('brouillon'); 
            // Date de génération 
            $table->date('date_generation')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('employe_id'); 
            $table->index('periode'); 
            $table->index('annee'); 
            $table->index('statut'); 
            // Éviter deux bulletins pour le même employé, 
            // la même période et la même année 
            $table->unique([ 
                'employe_id', 
                'periode', 
                'annee' 
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins_paie');
    }
};
