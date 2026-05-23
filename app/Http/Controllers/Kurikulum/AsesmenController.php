<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use App\Models\SubCpmk;
use App\Models\SubCpmkAsesmen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AsesmenController extends Controller
{
    use DosenMkScope;

    private function programStudiId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    /* ----------------------------------------------------------------
     * INDEX — daftar MK dengan status kelengkapan asesmen
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');

        $search = $request->input('search');

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks.subCpmks.asesmen'])
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()
            ->filter(fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty())
            ->map(function ($mk) {
                $subCpmks   = $mk->cpmks->flatMap->subCpmks;
                $total      = $subCpmks->count();
                $filled     = $subCpmks->filter(fn($sc) => $sc->asesmen !== null)->count();
                $mk->total_sub  = $total;
                $mk->filled_sub = $filled;
                return $mk;
            });

        return view('kurikulum.asesmen.index', compact('mataKuliahs', 'semester'));
    }

    /* ----------------------------------------------------------------
     * SHOW — grid input bobot asesmen per Sub-CPMK untuk 1 MK
     * ---------------------------------------------------------------- */
    public function show(MataKuliah $mataKuliah)
    {
        $this->authorizeDosenMk($mataKuliah);

        $cpmks = $mataKuliah->cpmks()
            ->with(['subCpmks.asesmen'])
            ->orderBy('urutan')
            ->get();

        return view('kurikulum.asesmen.show', compact('mataKuliah', 'cpmks'));
    }

    /* ----------------------------------------------------------------
     * SAVE — simpan bobot asesmen untuk 1 Sub-CPMK (AJAX, auto-save)
     * ---------------------------------------------------------------- */
    public function save(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $data = $request->validate([
            'tugas'        => 'required|numeric|min:0|max:100',
            'quis'         => 'required|numeric|min:0|max:100',
            'kasus_proyek' => 'required|numeric|min:0|max:100',
            'uts'          => 'required|numeric|min:0|max:100',
            'uas'          => 'required|numeric|min:0|max:100',
        ]);

        SubCpmkAsesmen::updateOrCreate(
            ['sub_cpmk_id' => $subCpmk->id],
            $data
        );

        $total = round(array_sum($data), 2);

        return response()->json(['ok' => true, 'total' => $total]);
    }
}
