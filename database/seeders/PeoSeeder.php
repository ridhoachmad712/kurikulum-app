<?php

namespace Database\Seeders;

use App\Models\Peo;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class PeoSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::where('kode', 'MNJ-S1')->first();
        if (! $prodi) return;

        foreach ([
            ['kode' => 'PEO1', 'nama' => 'Professional accomplishment', 'deskripsi' => 'Lulusan yang mampu menerapkan Ilmu Manajemen dan bisnis dalam penyelesaian masalah organisasi secara humanis', 'urutan' => 1],
            ['kode' => 'PEO2', 'nama' => 'Academic accomplishment', 'deskripsi' => 'Lulusan mampu beradaptasi dalam tantangan lingkungan global dengan belajar sepanjang hayat', 'urutan' => 2],
            ['kode' => 'PEO3', 'nama' => 'General/social accomplishment', 'deskripsi' => 'Lulusan mampu menunjukkan jiwa kepemimpinan dalam organisasi yang berlandaskan nilai Sipakatau, Sipakaingge, Sipakkalebbi', 'urutan' => 3],
        ] as $data) {
            Peo::firstOrCreate(
                ['program_studi_id' => $prodi->id, 'kode' => $data['kode']],
                $data + ['program_studi_id' => $prodi->id]
            );
        }
    }
}
