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
        Schema::create('retenues', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->cascadeOnDelete(); 
            // Type de retenue 
            $table->string('type_retenue'); 
            // Montant de la retenue 
            $table->decimal('montant', 15, 2); 
            // Date de la retenue 
            $table->date('date_retenue'); 
            // Statut 
            $table->string('statut')->default('active'); 
            // Motif 
            $table->string('motif')->nullable(); 
            // Commentaire 
            $table->text('commentaire')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('employe_id'); 
            $table->index('type_retenue'); 
            $table->index('date_retenue'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retenues');
    }
};
