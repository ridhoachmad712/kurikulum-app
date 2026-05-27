<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Buat tabel pivot cpmk_sub_cpmk ───────────────────────────────
        Schema::create('cpmk_sub_cpmk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sub_cpmk_id')->constrained()->cascadeOnDelete();
            $table->decimal('bobot', 6, 2)->nullable();
            $table->unique(['cpmk_id', 'sub_cpmk_id']);
        });

        // ── 2. Migrasi data: isi pivot dari cpmk_id & bobot yang sudah ada ──
        DB::statement('
            INSERT INTO cpmk_sub_cpmk (cpmk_id, sub_cpmk_id, bobot)
            SELECT cpmk_id, id, bobot
            FROM sub_cpmks
            WHERE cpmk_id IS NOT NULL
        ');

        // ── 3. Re-number urutan: sequential per MK (urut berdasar cpmk lama)─
        $mkIds = DB::table('sub_cpmks')->distinct()->pluck('mata_kuliah_id');
        foreach ($mkIds as $mkId) {
            $ids = DB::table('sub_cpmks')
                ->where('mata_kuliah_id', $mkId)
                ->orderBy('cpmk_id')
                ->orderBy('urutan')
                ->pluck('id');

            foreach ($ids as $i => $scId) {
                DB::table('sub_cpmks')->where('id', $scId)->update(['urutan' => $i + 1]);
            }
        }

        // ── 4. Hapus kolom cpmk_id & bobot dari sub_cpmks ──────────────────
        Schema::table('sub_cpmks', function (Blueprint $table) {
            $table->dropForeign(['cpmk_id']);
            $table->dropColumn(['cpmk_id', 'bobot']);
        });
    }

    public function down(): void
    {
        Schema::table('sub_cpmks', function (Blueprint $table) {
            $table->foreignId('cpmk_id')->nullable()->after('mata_kuliah_id')->constrained()->cascadeOnDelete();
            $table->decimal('bobot', 6, 2)->nullable()->after('urutan');
        });

        DB::statement('
            UPDATE sub_cpmks sc
            INNER JOIN (
                SELECT sub_cpmk_id, MIN(cpmk_id) AS cpmk_id, MAX(bobot) AS bobot
                FROM cpmk_sub_cpmk GROUP BY sub_cpmk_id
            ) p ON p.sub_cpmk_id = sc.id
            SET sc.cpmk_id = p.cpmk_id, sc.bobot = p.bobot
        ');

        Schema::dropIfExists('cpmk_sub_cpmk');
    }
};
