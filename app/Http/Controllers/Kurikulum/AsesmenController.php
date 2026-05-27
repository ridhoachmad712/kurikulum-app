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
            ->with(['cpmks.subCpmks.asesmen', 'cpmks.subCpmks.subCpmkClos'])
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
                $subCpmks        = $mk->cpmks->flatMap->subCpmks->unique('id');
                $withClo         = $subCpmks->filter(fn($sc) => $sc->subCpmkClos->isNotEmpty());
                $mk->all_sub     = $subCpmks->count();   // total sub termasuk yang belum ber-CLO
                $mk->total_sub   = $withClo->count();    // hanya sub yang sudah ber-CLO
                $mk->filled_sub  = $withClo->filter(fn($sc) => $sc->asesmen !== null)->count();
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
            ->with(['subCpmks.asesmen', 'subCpmks.subCpmkClos', 'cpmkCpls'])
            ->orderBy('urutan')
            ->get();

        // Bobot teoritis per Sub-CPMK = total bobot CPMK terhadap CPL / jumlah Sub-CPMK
        // Sama dengan kalkulasi di PengukuranController::cpmk()
        $subCpmkBobot = [];   // [sub_cpmk_id => bobot_teoritis]
        $cpmkMeta     = [];   // [cpmk_id => ['total_bobot', 'bobot_per_sub', 'sub_count']]

        foreach ($cpmks as $cpmk) {
            $totalBobot  = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
            $subCount    = $cpmk->subCpmks->count();
            $bobotPerSub = $subCount > 0 ? round($totalBobot / $subCount, 2) : 0;
            $totalManual = $cpmk->subCpmks->sum(fn($s) => (float) ($s->pivot->bobot ?? 0));

            $cpmkMeta[$cpmk->id] = [
                'total_bobot'   => $totalBobot,
                'bobot_per_sub' => $bobotPerSub,
                'sub_count'     => $subCount,
                'total_manual'  => round($totalManual, 2),
            ];

            foreach ($cpmk->subCpmks as $sc) {
                // Gunakan bobot pivot jika sudah diisi, fallback ke pembagian rata
                $subCpmkBobot[$sc->id] = $sc->pivot->bobot !== null ? (float) $sc->pivot->bobot : $bobotPerSub;
            }
        }

        return view('kurikulum.asesmen.show', compact('mataKuliah', 'cpmks', 'subCpmkBobot', 'cpmkMeta'));
    }

    /* ----------------------------------------------------------------
     * SAVE — simpan bobot asesmen untuk 1 Sub-CPMK (AJAX, auto-save)
     * ---------------------------------------------------------------- */
    public function save(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

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
