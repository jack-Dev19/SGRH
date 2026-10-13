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
        Schema::create('horaires_jours', function (Blueprint $table) {
            // Identifiant UUID 
            $table->uuid('id')->primary(); 
            // Horaire parent 
            $table->foreignUuid('horaire_id') 
                ->constrained('horaires_travail') 
                ->cascadeOnDelete(); 
            // Jour de la semaine : 
            // 1 = lundi 
            // 2 = mardi 
            // 3 = mercredi 
            // 4 = jeudi 
            // 5 = vendredi 
            // 6 = samedi 
            // 7 = dimanche 
            $table->unsignedTinyInteger('jour_semaine'); 
            // Indique si le jour est travaillé 
            $table->boolean('est_travaille')->default(true); 
            // Horaires de travail 
            $table->time('heure_debut')->nullable(); 
            $table->time('heure_fin')->nullable(); 
            // Pause 
            $table->time('debut_pause')->nullable(); 
            $table->time('fin_pause')->nullable(); 
            // Nombre d'heures prévues 
            $table->decimal('heures_prevues', 5, 2)->nullable(); 
            // Empêcher deux configurations 
            // pour le même jour dans un même horaire 
            $table->unique(['horaire_id', 'jour_semaine']); 
            // Dates Laravel 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaires_jours');
    }
};
