<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->boolean('est_anomalie')
                ->default(false)
                ->after('source');

            $table->text('description_anomalie')
                ->nullable()
                ->after('est_anomalie');
        });
    }

    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn([
                'est_anomalie',
                'description_anomalie',
            ]);
        });
    }
};