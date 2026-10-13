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
        Schema::create('historiques_actions', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Utilisateur ayant effectué l'action 
            $table->foreignUuid('user_id') 
                ->nullable() 
                ->constrained('users') 
                ->nullOnDelete(); 
            // Action effectuée 
            $table->string('action'); 
            // Module concerné 
            $table->string('module'); 
            // Description de l'action 
            $table->text('description'); 
            // Type de la donnée concernée 
            $table->string('type_cible')->nullable(); 
            // Identifiant de la donnée concernée 
            $table->uuid('cible_id')->nullable(); 
            // Anciennes données 
            $table->json('ancienne_valeur')->nullable(); 
            // Nouvelles données 
            $table->json('nouvelle_valeur')->nullable(); 
            // Adresse IP 
            $table->string('adresse_ip')->nullable(); 
            // Navigateur / appareil 
            $table->text('user_agent')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('user_id'); 
            $table->index('action'); 
            $table->index('module'); 
            $table->index('type_cible'); 
            $table->index('cible_id'); 
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques_actions');
    }
};
