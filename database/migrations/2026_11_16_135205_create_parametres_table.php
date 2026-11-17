
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table des paramètres du SGRH.
     */
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {

            // Identifiant UUID
            $table->uuid('id')->primary();

            // Module concerné : general, notifications, securite...
            $table->string('groupe', 100);

            // Clé unique du paramètre dans son groupe
            $table->string('cle', 150);

            // Valeur du paramètre (texte, nombre, booléen ou JSON)
            $table->longText('valeur')->nullable();

            // Dates de création et de modification
            $table->timestamps();

            // Évite les doublons dans un même groupe
            $table->unique(['groupe', 'cle']);
        });
    }

    /**
     * Suppression de la table.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
