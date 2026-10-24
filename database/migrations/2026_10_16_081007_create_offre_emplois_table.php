<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offre_emplois', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Infos principales
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');                  // description courte
            $table->text('missions')->nullable();         // missions détaillées
            $table->text('profil_recherche')->nullable(); // profil demandé

            // Type
            $table->enum('type_contrat', ['CDI', 'CDD', 'Stage', 'Freelance', 'Intérim']);
            $table->string('lieu')->default('Casablanca');
            $table->string('experience')->nullable();
            $table->string('niveau_etude')->nullable();
            $table->string('salaire')->nullable();

            // Deadline
            $table->date('date_limite')->nullable();

            // Contact
            $table->string('email_contact');

            // Statut
            $table->enum('statut', ['brouillon', 'publiee', 'archivee'])->default('brouillon');
            $table->timestamp('publiee_le')->nullable();

            // Créé par
            $table->uuid('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('statut');
            $table->index('type_contrat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offre_emplois');
    }
};