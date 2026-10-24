<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pointages', function (Blueprint $table) {
            // Source du pointage : 'qr_code' ou 'manuel'
            $table->string('type_scan', 20)->default('manuel')->after('type');

            // Référence au QR code utilisé (nullable si manuel)
            $table->foreignUuid('qr_code_id')
                ->nullable()
                ->after('type_scan')
                ->constrained('qr_codes')
                ->nullOnDelete();

            // Marquer une anomalie
            $table->boolean('est_anomalie')->default(false)->after('qr_code_id');
            $table->text('description_anomalie')->nullable()->after('est_anomalie');
        });
    }

    public function down(): void
    {
        Schema::table('pointages', function (Blueprint $table) {
            $table->dropForeign(['qr_code_id']);
            $table->dropColumn(['type_scan', 'qr_code_id', 'est_anomalie', 'description_anomalie']);
        });
    }
};