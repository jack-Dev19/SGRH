<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            // ✅ Clé primaire en UUID (36 caractères)
            $table->uuid('id')->primary();

            // ✅ Relations avec foreignUuid
            $table->foreignUuid('employe_id')
                  ->constrained('employes')
                  ->onDelete('cascade');

            $table->foreignUuid('contrat_id')
                  ->nullable()
                  ->constrained('contrats')
                  ->onDelete('set null');

            // Catégorie
            $table->enum('categorie', [
                'photo_identite',
                'photo_cin',
                'contrat',
                'fiche_paie',
                'diplome',
                'attestation',
                'autre',
            ]);

            // Métadonnées
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('fichier_path');
            $table->string('fichier_nom_original');
            $table->string('fichier_mime', 100);
            $table->unsignedBigInteger('fichier_taille');

            // Période (fiches de paie)
            $table->unsignedTinyInteger('mois')->nullable();
            $table->unsignedSmallInteger('annee')->nullable();

            // Uploadé par
            $table->foreignUuid('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['employe_id', 'categorie']);
            $table->index(['employe_id', 'mois', 'annee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};