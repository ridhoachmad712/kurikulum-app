<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    private function programStudiId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    /* ----------------------------------------------------------------
     * INDEX — kelola penugasan dosen per MK
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');

        $search = $request->input('search');

        $mataKuliahs = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['dosens'])
            ->withCount(['cpmks', 'subCpmks'])
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        $dosens = User::role('dosen')
            ->where('program_studi_id', $psId)
            ->orderBy('name')
            ->get(['id', 'name', 'nip']);

        return view('admin.penugasan.index', compact('mataKuliahs', 'dosens', 'semester'));
    }

    /* ----------------------------------------------------------------
     * TOGGLE — assign/unassign dosen ke MK (AJAX)
     * ---------------------------------------------------------------- */
    public function toggle(Request $request, MataKuliah $mataKuliah): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $exists = $mataKuliah->dosens()->where('user_id', $data['user_id'])->exists();

        if ($exists) {
            $mataKuliah->dosens()->detach($data['user_id']);
            $attached = false;
        } else {
            $mataKuliah->dosens()->attach($data['user_id']);
            $attached = true;
        }

        $dosens = $mataKuliah->dosens()->get(['users.id', 'name', 'nip']);

        return response()->json(['ok' => true, 'attached' => $attached, 'dosens' => $dosens]);
    }
}
