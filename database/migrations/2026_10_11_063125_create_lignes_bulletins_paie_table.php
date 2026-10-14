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
        Schema::create('lignes_bulletins_paie', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->foreignUuid('bulletin_paie_id')->constrained('bulletins_paie')->cascadeOnDelete(); 
            $table->string('type',30);
            $table->string('libelle', 150); 
            $table->decimal('quantite', 10, 2)->nullable(); 
            $table->decimal('taux', 10, 2)->nullable(); 
            $table->decimal('montant', 15, 2); 
            $table->timestamps(); 
            $table->index('bulletin_paie_id'); 
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_bulletins_paie');
    }
};
