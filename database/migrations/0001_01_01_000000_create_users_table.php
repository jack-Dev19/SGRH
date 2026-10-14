<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Création des tables d'authentification du SGRH.
 *
 * ⚠️ Adaptations importantes par rapport à Laravel par défaut :
 * - `users.id` est un UUID (pas un bigint auto-incrémenté).
 * - `users.matricule` : identifiant métier unique (ADM001, RH001, ...).
 * - `users.role_id`  : FK UUID vers `roles`.
 * - `users.statut`   : permet de désactiver un compte sans le supprimer.
 * - `sessions.user_id` : UUID (et non foreignId bigint) pour matcher users.id.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------
        // TABLE : users
        // ---------------------------------------------------------
        Schema::create('users', function (Blueprint $table) {
            // Clé primaire UUID
            $table->uuid('id')->primary();

            // Identité
            $table->string('name');
            $table->string('email')->unique();
            $table->string('matricule')->unique(); // ex: ADM001

            // Lien vers le rôle (FK UUID)
            $table->foreignUuid('role_id')
                  ->nullable()
                  ->constrained('roles')
                  ->nullOnDelete();

            // Authentification
            $table->string('password');
            $table->boolean('statut')->default(true); // true = actif
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // ---------------------------------------------------------
        // TABLE : password_reset_tokens
        // ---------------------------------------------------------
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ---------------------------------------------------------
        // TABLE : sessions (⚠️ user_id en UUID)
        // ---------------------------------------------------------
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index(); // ← UUID, PAS foreignId
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};