<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\MataKuliah;
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

        $fields = ['tugas', 'quis', 'kasus_proyek', 'uts', 'uas'];

        $cpmkMeta    = [];   // [cpmk_id => total_bobot, sub_count, no_clo]
        $cpmkAsesmen = [];   // [cpmk_id => [tugas, quis, ..., total]] — agregat dari semua Sub-CPMK

        foreach ($cpmks as $cpmk) {
            $totalBobot = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
            $subCount   = $cpmk->subCpmks->count();

            // Agregat asesmen CPMK = jumlah nilai metode seluruh Sub-CPMK-nya
            $agg = array_fill_keys($fields, 0.0);
            foreach ($cpmk->subCpmks as $sc) {
                if ($sc->asesmen) {
                    foreach ($fields as $f) $agg[$f] += (float) $sc->asesmen->{$f};
                }
            }
            $agg = array_map(fn ($v) => round($v, 2), $agg);
            $agg['total'] = round(array_sum($agg), 2);

            $cpmkAsesmen[$cpmk->id] = $agg;
            $cpmkMeta[$cpmk->id] = [
                'total_bobot' => $totalBobot,
                'sub_count'   => $subCount,
                'no_clo'      => $cpmk->subCpmks->every(fn ($sc) => $sc->subCpmkClos->isEmpty()),
            ];
        }

        return view('kurikulum.asesmen.show', compact('mataKuliah', 'cpmks', 'cpmkMeta', 'cpmkAsesmen'));
    }

    /* ----------------------------------------------------------------
     * SAVE — simpan asesmen di level CPMK (dibagi rata ke Sub-CPMK)
     * ---------------------------------------------------------------- */
    public function saveCpmk(Request $request, Cpmk $cpmk): JsonResponse
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $data = $request->validate([
            'tugas'        => 'required|numeric|min:0|max:100',
            'quis'         => 'required|numeric|min:0|max:100',
            'kasus_proyek' => 'required|numeric|min:0|max:100',
            'uts'          => 'required|numeric|min:0|max:100',
            'uas'          => 'required|numeric|min:0|max:100',
        ]);

        $subs = $cpmk->subCpmks()->get();
        $n    = $subs->count();
        if ($n === 0) {
            return response()->json(['ok' => false, 'message' => 'CPMK ini belum memiliki Sub-CPMK.'], 422);
        }

        $fields = ['tugas', 'quis', 'kasus_proyek', 'uts', 'uas'];

        // Bagi rata tiap metode ke semua Sub-CPMK; sisa pembulatan ke sub terakhir agar jumlah persis.
        $per = [];
        foreach ($fields as $f) {
            $per[$f] = round($data[$f] / $n, 2);
        }
        foreach ($subs->values() as $i => $sc) {
            $vals = [];
            foreach ($fields as $f) {
                $vals[$f] = ($i === $n - 1)
                    ? round($data[$f] - $per[$f] * ($n - 1), 2)   // sub terakhir menyerap sisa
                    : $per[$f];
            }
            SubCpmkAsesmen::updateOrCreate(['sub_cpmk_id' => $sc->id], $vals);
        }

        return response()->json(['ok' => true, 'total' => round(array_sum($data), 2)]);
    }
}
