<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('plos', 'peos');
        $this->renameCodes('PLO', 'PEO');
    }

    public function down(): void
    {
        $this->renameCodes('PEO', 'PLO');
        Schema::rename('peos', 'plos');
    }

    private function renameCodes(string $from, string $to): void
    {
        $items = DB::table('peos')->where('kode', 'like', $from . '%')->get(['id', 'program_studi_id', 'kode']);

        foreach ($items as $item) {
            if (! preg_match('/^' . $from . '(\d+)$/', $item->kode, $matches)) {
                continue;
            }

            $newCode = $to . $matches[1];
            $exists = DB::table('peos')
                ->where('program_studi_id', $item->program_studi_id)
                ->where('kode', $newCode)
                ->exists();

            if (! $exists) {
                DB::table('peos')->where('id', $item->id)->update(['kode' => $newCode]);
            }
        }
    }
};
