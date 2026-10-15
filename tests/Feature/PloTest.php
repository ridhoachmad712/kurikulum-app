<?php

namespace Tests\Feature;

use App\Models\Plo;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PloTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_displays_plos_in_order(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        Plo::create(['program_studi_id' => $prodi->id, 'kode' => 'PLO2', 'nama' => 'Kedua', 'deskripsi' => 'Deskripsi kedua', 'urutan' => 2]);
        Plo::create(['program_studi_id' => $prodi->id, 'kode' => 'PLO1', 'nama' => 'Pertama', 'deskripsi' => 'Deskripsi pertama', 'urutan' => 1]);

        $this->get('/plo')->assertOk()->assertSeeInOrder(['Pertama', 'Kedua']);
    }

    public function test_admin_can_update_own_prodi_but_cannot_edit_another_prodi(): void
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        $other = ProgramStudi::create(['kode' => 'OTHER-S1', 'nama' => 'Prodi Lain', 'jenjang' => 'S1', 'aktif' => false]);
        Role::findOrCreate('admin', 'web');
        $admin = User::factory()->create(['program_studi_id' => $prodi->id]);
        $admin->assignRole('admin');

        $this->actingAs($admin)->post(route('master.plo.store'), [
            'kode' => 'PLO4', 'nama' => 'Baru', 'deskripsi' => 'Rumusan baru', 'urutan' => 4,
        ])->assertRedirect(route('master.plo.index'));
        $this->assertDatabaseHas('plos', ['program_studi_id' => $prodi->id, 'kode' => 'PLO4']);

        $foreignPlo = Plo::create(['program_studi_id' => $other->id, 'kode' => 'PLO4', 'nama' => 'Asing', 'deskripsi' => 'Prodi lain', 'urutan' => 4]);
        $this->actingAs($admin)->patch(route('master.plo.update', $foreignPlo), [
            'kode' => 'PLO4', 'nama' => 'Diubah', 'deskripsi' => 'Tidak boleh', 'urutan' => 4,
        ])->assertNotFound();
        $this->assertDatabaseHas('plos', ['id' => $foreignPlo->id, 'nama' => 'Asing']);
    }
}
