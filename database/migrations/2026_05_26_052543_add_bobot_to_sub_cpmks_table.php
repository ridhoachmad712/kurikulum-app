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
        Schema::table('sub_cpmks', function (Blueprint $table) {
            $table->decimal('bobot', 6, 2)->nullable()->default(null)->after('kode_clo_tertinggi');
        });
    }

    public function down(): void
    {
        Schema::table('sub_cpmks', function (Blueprint $table) {
            $table->dropColumn('bobot');
        });
    }
};
