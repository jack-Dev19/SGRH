<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            // Clé primaire UUID
            $table->uuid('id')->primary();

            // Employé associé
            $table->foreignUuid('employe_id')
                ->constrained('employes')
                ->cascadeOnDelete();

            // Code court unique (affiché à l'utilisateur, ex: "A3F9K2")
            $table->string('code', 64)->unique();

            // Token secret long (utilisé pour la vérification, jamais affiché)
            $table->string('token', 128)->unique();

            // Contenu complet encodé dans le QR (URL + token)
            $table->text('contenu_qr');

            // Statut
            $table->boolean('actif')->default(true);

            // Traçabilité : qui a généré
            $table->foreignUuid('genere_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('genere_le')->nullable();

            // Traçabilité : révocation
            $table->timestamp('revoque_le')->nullable();

            $table->foreignUuid('revoque_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('raison_revocation')->nullable();

            // Compteur d'utilisations
            $table->unsignedInteger('scan_count')->default(0);
            $table->timestamp('dernier_scan_le')->nullable();

            // Index
            $table->index(['employe_id', 'actif']);
            $table->index('actif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};