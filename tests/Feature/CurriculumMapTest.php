<?php

namespace Tests\Feature;

use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculumMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_map_uses_active_courses_and_existing_cpl_relationships(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Manajemen Uji', 'jenjang' => 'S1', 'aktif' => true]);
        $otherProdi = ProgramStudi::create(['kode' => 'OTHER-S1', 'nama' => 'Prodi Lain', 'jenjang' => 'S1', 'aktif' => false]);
        $cpl = Cpl::create(['program_studi_id' => $prodi->id, 'kode' => 'CPL1', 'deskripsi' => 'Kemampuan uji', 'urutan' => 1]);
        $active = MataKuliah::create([
            'program_studi_id' => $prodi->id, 'kode_rps' => 'MK001', 'nama' => 'Mata Kuliah Aktif',
            'sks' => 3, 'semester' => 2, 'konsentrasi' => 'Manajemen Keuangan', 'aktif' => true,
        ]);
        $active->cpls()->attach($cpl->id, ['level' => 'Tinggi']);
        MataKuliah::create([
            'program_studi_id' => $prodi->id, 'kode_rps' => 'MK002', 'nama' => 'Mata Kuliah Nonaktif',
            'sks' => 2, 'semester' => 3, 'aktif' => false,
        ]);
        MataKuliah::create([
            'program_studi_id' => $otherProdi->id, 'kode_rps' => 'MK003', 'nama' => 'Mata Kuliah Prodi Lain',
            'sks' => 2, 'semester' => 1, 'aktif' => true,
        ]);

        $this->get(route('public.peta-kurikulum'))
            ->assertOk()
            ->assertSee('Peta Kurikulum')
            ->assertSee('Mata Kuliah Aktif')
            ->assertSee('Manajemen Keuangan')
            ->assertSee('CPL1')
            ->assertSee('Tinggi')
            ->assertDontSee('Mata Kuliah Nonaktif')
            ->assertDontSee('Mata Kuliah Prodi Lain');
    }
}
