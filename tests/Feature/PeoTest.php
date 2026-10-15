<?php

namespace Tests\Feature;

use App\Models\Peo;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_displays_peos_in_order(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        Peo::create(['program_studi_id' => $prodi->id, 'kode' => 'PEO2', 'nama' => 'Kedua', 'deskripsi' => 'Deskripsi kedua', 'urutan' => 2]);
        Peo::create(['program_studi_id' => $prodi->id, 'kode' => 'PEO1', 'nama' => 'Pertama', 'deskripsi' => 'Deskripsi pertama', 'urutan' => 1]);

        $this->get('/peo')->assertOk()->assertSeeInOrder(['Pertama', 'Kedua']);
        $this->get('/plo')->assertRedirect(route('public.peo'));
    }

    public function test_homepage_shows_admin_peos_and_labels_available_courses(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        Peo::create(['program_studi_id' => $prodi->id, 'kode' => 'PEO1', 'nama' => 'Profesional Uji', 'deskripsi' => 'Tujuan dari admin', 'urutan' => 1]);
        MataKuliah::create(['program_studi_id' => $prodi->id, 'kode_rps' => 'MK001', 'nama' => 'Mata Kuliah Aktif', 'sks' => 3, 'semester' => 1, 'aktif' => true]);
        MataKuliah::create(['program_studi_id' => $prodi->id, 'kode_rps' => 'MK002', 'nama' => 'Mata Kuliah Nonaktif', 'sks' => 2, 'semester' => 1, 'aktif' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Profesional Uji')
            ->assertSee('Tujuan dari admin')
            ->assertSee('1 mata kuliah aktif')
            ->assertSee('total 3 SKS');
    }

    public function test_existing_plo_is_renamed_without_losing_admin_edits(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        Schema::rename('peos', 'plos');
        DB::table('plos')->insert([
            'program_studi_id' => $prodi->id,
            'kode' => 'PLO1',
            'nama' => 'Judul hasil suntingan admin',
            'deskripsi' => 'Deskripsi hasil suntingan admin',
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $migration = require database_path('migrations/2026_10_15_000002_rename_plos_to_peos.php');
        $migration->up();

        $this->assertDatabaseHas('peos', [
            'program_studi_id' => $prodi->id,
            'kode' => 'PEO1',
            'nama' => 'Judul hasil suntingan admin',
            'deskripsi' => 'Deskripsi hasil suntingan admin',
        ]);
    }

    public function test_admin_can_update_own_prodi_but_cannot_edit_another_prodi(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        $other = ProgramStudi::create(['kode' => 'OTHER-S1', 'nama' => 'Prodi Lain', 'jenjang' => 'S1', 'aktif' => false]);
        Role::findOrCreate('admin', 'web');
        $admin = User::factory()->create(['program_studi_id' => $prodi->id]);
        $admin->assignRole('admin');

        $this->actingAs($admin)->post(route('master.peo.store'), [
            'kode' => 'PEO4', 'nama' => 'Baru', 'deskripsi' => 'Rumusan baru', 'urutan' => 4,
        ])->assertRedirect(route('master.peo.index'));
        $this->assertDatabaseHas('peos', ['program_studi_id' => $prodi->id, 'kode' => 'PEO4']);

        $foreignPeo = Peo::create(['program_studi_id' => $other->id, 'kode' => 'PEO4', 'nama' => 'Asing', 'deskripsi' => 'Prodi lain', 'urutan' => 4]);
        $this->actingAs($admin)->patch(route('master.peo.update', $foreignPeo), [
            'kode' => 'PEO4', 'nama' => 'Diubah', 'deskripsi' => 'Tidak boleh', 'urutan' => 4,
        ])->assertNotFound();
        $this->assertDatabaseHas('peos', ['id' => $foreignPeo->id, 'nama' => 'Asing']);
    }
}
