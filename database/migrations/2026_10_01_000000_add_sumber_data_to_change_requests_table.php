<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->string('sumber_data', 50)->nullable()->after('permintaan_fitur');
        });

        // Baris hasil import Excel SIMRS dicap supaya UI menandai status pending
        // sebagai "Data Migrasi". Request manual dengan permintaan_fitur SIMRS
        // tetap terbaca sebagai pending biasa.
        DB::table('change_requests')
            ->where('permintaan_fitur', 'SIMRS')
            ->whereNull('sumber_data')
            ->update(['sumber_data' => 'Migrasi SIMRS']);
    }

    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn('sumber_data');
        });
    }
};