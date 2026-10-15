<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_studi_id')->constrained('program_studis')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama', 255);
            $table->text('deskripsi');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique(['program_studi_id', 'kode']);
        });

        // Produksi sudah memiliki prodi ketika migrasi baru ini dijalankan.
        // Pada instalasi baru, PloSeeder mengisi data setelah ProgramStudiSeeder.
        $prodiId = DB::table('program_studis')->where('kode', 'MNJ-S1')->value('id');
        if ($prodiId) {
            $now = now();
            DB::table('plos')->insert([
                ['program_studi_id' => $prodiId, 'kode' => 'PLO1', 'nama' => 'Professional accomplishment', 'deskripsi' => 'Lulusan yang mampu menerapkan Ilmu Manajemen dan bisnis dalam penyelesaian masalah organisasi secara humanis', 'urutan' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['program_studi_id' => $prodiId, 'kode' => 'PLO2', 'nama' => 'Academic accomplishment', 'deskripsi' => 'Lulusan mampu beradaptasi dalam tantangan lingkungan global dengan belajar sepanjang hayat', 'urutan' => 2, 'created_at' => $now, 'updated_at' => $now],
                ['program_studi_id' => $prodiId, 'kode' => 'PLO3', 'nama' => 'General/social accomplishment', 'deskripsi' => 'Lulusan mampu menunjukkan jiwa kepemimpinan dalam organisasi yang berlandaskan nilai Sipakatau, Sipakaingge, Sipakkalebbi', 'urutan' => 3, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plos');
    }
};
