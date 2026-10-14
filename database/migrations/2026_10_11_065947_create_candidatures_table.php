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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('candidat_id') ->constrained('candidats') ->cascadeOnDelete();
            $table->foreignUuid('offre_emploi_id') ->constrained('offres_emploi') ->cascadeOnDelete();
            $table->date('date_candidature');
            $table->string('statut')->default('nouvelle');
            $table->text('commentaire')->nullable();
            $table->timestamps();
            $table->index('offre_emploi_id'); 
            $table->index('date_candidature'); 
            $table->index('statut');
            $table->unique([ 'candidat_id', 'offre_emploi_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
