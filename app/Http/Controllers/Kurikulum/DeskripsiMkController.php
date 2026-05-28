<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class DeskripsiMkController extends Controller
{
    /**
     * Tampilkan daftar MK + status deskripsinya.
     * Dosen: hanya MK yang diampu.
     * Admin/Kaprodi: semua MK aktif di prodi.
     */
    public function index()
    {
        $user = auth()->user();
        $psId = (int) $user->program_studi_id;

        $query = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with('dosens:id,name')
            ->orderBy('semester')
            ->orderBy('urutan');

        if ($user->hasRole('dosen')) {
            $query->whereHas('dosens', fn($q) => $q->where('users.id', $user->id));
        }

        $mataKuliahs = $query->get();

        return view('kurikulum.deskripsi.index', compact('mataKuliahs'));
    }

    /**
     * Form edit deskripsi satu MK.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        $this->authorizeAccess($mataKuliah);

        return view('kurikulum.deskripsi.edit', compact('mataKuliah'));
    }

    /**
     * Simpan deskripsi.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $this->authorizeAccess($mataKuliah);

        $request->validate([
            'deskripsi' => ['nullable', 'string', 'max:3000'],
        ]);

        $mataKuliah->update(['deskripsi' => $request->deskripsi]);

        return redirect()
            ->route('kurikulum.deskripsi.index')
            ->with('success', 'Deskripsi mata kuliah "' . $mataKuliah->nama . '" berhasil disimpan.');
    }

    /**
     * Pastikan user boleh mengakses MK ini.
     * Dosen: hanya MK yang diampu.
     * Admin/Kaprodi: semua MK di prodi-nya.
     */
    private function authorizeAccess(MataKuliah $mataKuliah): void
    {
        $user = auth()->user();

        if ((int) $mataKuliah->program_studi_id !== (int) $user->program_studi_id) {
            abort(403);
        }

        if ($user->hasRole('dosen')) {
            $ditugaskan = $mataKuliah->dosens()->where('users.id', $user->id)->exists();
            if (!$ditugaskan) {
                abort(403, 'Anda tidak ditugaskan pada mata kuliah ini.');
            }
        }
    }
}
