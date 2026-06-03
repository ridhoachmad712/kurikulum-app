<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sub_cpmk_asesmens', function (Blueprint $table) {
            $table->decimal('aktivitas_partisipatif', 5, 2)->default(0)->after('sub_cpmk_id');
        });
    }

    public function down(): void
    {
        Schema::table('sub_cpmk_asesmens', function (Blueprint $table) {
            $table->dropColumn('aktivitas_partisipatif');
        });
    }
};
