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
        Schema::create('notifications', function (Blueprint $table) {
             // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Utilisateur destinataire 
            $table->foreignUuid('user_id') 
                ->constrained('users') 
                ->cascadeOnDelete(); 
            // Titre 
            $table->string('titre'); 
            // Message 
            $table->text('message'); 
            // Type de notification 
            $table->string('type');
            // Notification lue ou non 
            $table->boolean('lue')->default(false); 
            // Date de lecture 
            $table->timestamp('date_lecture')->nullable(); 
            // URL vers la ressource concernée 
            $table->string('url')->nullable(); 
            // Dates Laravel 
            $table->timestamps(); 
            // Index 
            $table->index('user_id'); 
            $table->index('type'); 
            $table->index('lue'); 
            $table->index('created_at'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
