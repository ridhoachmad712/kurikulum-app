<?php

namespace Tests\Feature;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RpsDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RpsDocumentTest extends TestCase
{
    use RefreshDatabase;

    private function setupCourse(): array
    {
        $prodi = ProgramStudi::create(['kode' => 'TEST-S1', 'nama' => 'Prodi Uji', 'jenjang' => 'S1', 'aktif' => true]);
        $course = MataKuliah::create(['program_studi_id' => $prodi->id, 'kode_rps' => 'MK001', 'nama' => 'Manajemen Uji', 'sks' => 3, 'semester' => 1, 'aktif' => true]);
        Role::findOrCreate('dosen', 'web');
        $lecturer = User::factory()->create(['program_studi_id' => $prodi->id]);
        $lecturer->assignRole('dosen');
        $lecturer->mataKuliahs()->attach($course);

        return [$prodi, $course, $lecturer];
    }

    private function pdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('rps.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF");
    }

    public function test_assigned_lecturer_uploads_pdf_and_public_can_open_it(): void
    {
        Storage::fake('local');
        [, $course, $lecturer] = $this->setupCourse();

        $this->actingAs($lecturer)->get(route('kurikulum.rps-documents.index'))
            ->assertOk()->assertSee('Manajemen Uji');
        $this->actingAs($lecturer)->post(route('kurikulum.rps-documents.store', $course), [
            'file' => $this->pdf(),
        ])->assertRedirect(route('kurikulum.rps-documents.index'));

        $document = RpsDocument::sole();
        Storage::disk('local')->assertExists($document->file_path);
        $this->get(route('public.mata-kuliah'))->assertOk()
            ->assertSee(route('public.mata-kuliah.rps', $course));
        $this->get(route('public.mata-kuliah.rps', $course))->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $oldPath = $document->file_path;
        $this->actingAs($lecturer)->post(route('kurikulum.rps-documents.store', $course), [
            'file' => $this->pdf(),
        ])->assertRedirect();
        $this->assertSame(1, RpsDocument::count());
        Storage::disk('local')->assertMissing($oldPath);
    }

    public function test_unassigned_lecturer_cannot_upload_and_non_pdf_is_rejected(): void
    {
        Storage::fake('local');
        [$prodi, $course, $lecturer] = $this->setupCourse();
        $other = User::factory()->create(['program_studi_id' => $prodi->id]);
        $other->assignRole('dosen');

        $this->actingAs($other)->post(route('kurikulum.rps-documents.store', $course), [
            'file' => $this->pdf(),
        ])->assertForbidden();
        $this->actingAs($lecturer)->post(route('kurikulum.rps-documents.store', $course), [
            'file' => UploadedFile::fake()->createWithContent('catatan.txt', 'bukan PDF'),
        ])->assertSessionHasErrors('file');
        $this->assertDatabaseCount('rps_documents', 0);
        $this->get(route('public.mata-kuliah.rps', $course))->assertNotFound();
    }
}
