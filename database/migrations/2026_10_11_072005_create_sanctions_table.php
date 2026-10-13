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
        Schema::create('sanctions', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Employé concerné 
            $table->foreignUuid('employe_id') 
                ->constrained('employes') 
                ->restrictOnDelete(); 
            // Utilisateur ayant enregistré la sanction 
            $table->foreignUuid('user_id') 
                ->constrained('users') 
                ->restrictOnDelete(); 
            // Type de sanction 
            $table->string('type_sanction'); 
            // Motif 
            $table->text('motif'); 
            // Date de la sanction 
            $table->date('date_sanction'); 
            // Période de la sanction 
            $table->date('date_debut')->nullable(); 
            $table->date('date_fin')->nullable(); 
            // Description détaillée 
            $table->text('description')->nullable(); 
            // Décision prise 
            $table->text('decision')->nullable(); 
            // Statut 
            $table->string('statut')->default('active'); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('employe_id'); 
            $table->index('user_id'); 
            $table->index('type_sanction'); 
            $table->index('date_sanction'); 
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanctions');
    }
};
