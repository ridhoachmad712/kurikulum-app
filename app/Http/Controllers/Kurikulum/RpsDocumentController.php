<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\RpsDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RpsDocumentController extends Controller
{
    public function index()
    {
        $mataKuliahs = auth()->user()->mataKuliahs()
            ->where('mata_kuliahs.program_studi_id', auth()->user()->program_studi_id)
            ->where('mata_kuliahs.aktif', true)
            ->with(['rpsDocument.uploader:id,name'])
            ->orderBy('mata_kuliahs.semester')
            ->orderBy('mata_kuliahs.urutan')
            ->get();

        return view('kurikulum.rps-documents.index', compact('mataKuliahs'));
    }

    public function store(Request $request, MataKuliah $mataKuliah)
    {
        $user = $request->user();
        abort_unless(
            $mataKuliah->aktif
            && $mataKuliah->program_studi_id === $user->program_studi_id
            && $user->mataKuliahs()->where('mata_kuliahs.id', $mataKuliah->id)->exists(),
            403
        );

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('rps/' . $mataKuliah->id, 'local');
        if (! $path) {
            throw ValidationException::withMessages(['file' => 'Berkas RPS gagal disimpan. Silakan coba lagi.']);
        }

        $oldPath = RpsDocument::where('mata_kuliah_id', $mataKuliah->id)->value('file_path');
        try {
            RpsDocument::updateOrCreate(
                ['mata_kuliah_id' => $mataKuliah->id],
                ['uploaded_by' => $user->id, 'file_path' => $path, 'original_name' => Str::limit($file->getClientOriginalName(), 255, '')]
            );
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        if ($oldPath && $oldPath !== $path) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()->route('kurikulum.rps-documents.index')->with('success', 'Berkas RPS berhasil diunggah.');
    }
}
