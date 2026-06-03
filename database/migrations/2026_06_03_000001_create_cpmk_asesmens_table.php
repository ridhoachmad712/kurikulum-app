<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cpmk_asesmens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->unique()->constrained('cpmks')->cascadeOnDelete();
            $table->decimal('aktivitas_partisipatif', 5, 2)->default(0);
            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('quis', 5, 2)->default(0);
            $table->decimal('kasus_proyek', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->timestamps();
        });

        // Backfill: agregasi asesmen Sub-CPMK yang ada menjadi asesmen level CPMK
        $fields = ['aktivitas_partisipatif', 'tugas', 'quis', 'kasus_proyek', 'uts', 'uas'];
        $now    = now();

        foreach (DB::table('cpmks')->pluck('id') as $cpmkId) {
            $subIds = DB::table('cpmk_sub_cpmk')->where('cpmk_id', $cpmkId)->pluck('sub_cpmk_id');
            if ($subIds->isEmpty()) continue;

            $rows = DB::table('sub_cpmk_asesmens')->whereIn('sub_cpmk_id', $subIds)->get();
            if ($rows->isEmpty()) continue;

            $agg = array_fill_keys($fields, 0.0);
            foreach ($rows as $r) {
                foreach ($fields as $f) $agg[$f] += (float) ($r->{$f} ?? 0);
            }
            if (array_sum($agg) <= 0) continue;

            DB::table('cpmk_asesmens')->insert(array_merge(
                ['cpmk_id' => $cpmkId, 'created_at' => $now, 'updated_at' => $now],
                array_map(fn ($v) => round($v, 2), $agg)
            ));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cpmk_asesmens');
    }
};
