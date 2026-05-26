<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\CpmkCpl;
use App\Models\MataKuliah;
use App\Models\MkCpl;
use App\Services\MatriksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    use DosenMkScope;

    private function programStudiId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    /* ----------------------------------------------------------------
     * INDEX — daftar semua MK dengan progress CPMK
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');

        $search = $request->input('search');

        $query = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->withCount(['cpmks'])
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('urutan');

        if ($semester) $query->where('semester', $semester);
        $this->applyDosenScope($query);

        $mataKuliahs = $query->paginate(20)->withQueryString();

        return view('kurikulum.cpmk.index', compact('mataKuliahs', 'semester'));
    }

    /* ----------------------------------------------------------------
     * SHOW — kelola CPMK untuk 1 MK
     * ---------------------------------------------------------------- */
    public function show(MataKuliah $mataKuliah)
    {
        $this->authorizeDosenMk($mataKuliah);

        // CPL yang terkait MK ini (dari mk_cpls)
        $mkCplIds = MkCpl::where('mata_kuliah_id', $mataKuliah->id)
            ->whereNotNull('level')
            ->pluck('cpl_id');

        $cpls = Cpl::where('program_studi_id', $mataKuliah->program_studi_id)
            ->whereIn('id', $mkCplIds)
            ->orderBy('urutan')
            ->get();

        $cpmks = Cpmk::where('mata_kuliah_id', $mataKuliah->id)
            ->with(['cpmkCpls', 'subCpmks'])
            ->orderBy('urutan')
            ->get();

        // Map [cpmk_id][cpl_id] => {has_relation, bobot_persen}
        $cpmkCplMap = [];
        foreach ($cpmks as $cpmk) {
            foreach ($cpmk->cpmkCpls as $cc) {
                $cpmkCplMap[$cpmk->id][$cc->cpl_id] = [
                    'has_relation' => (bool) $cc->has_relation,
                    'bobot'        => (float) $cc->bobot_persen,
                ];
            }
        }

        // Hitung referensi Tabel 5: % bobot per CPL dalam MK ini
        // (nilai / rowTotal × 100, sama persis dengan kalkulasi Tabel 5)
        $mkCplRows = MkCpl::where('mata_kuliah_id', $mataKuliah->id)
            ->whereIn('cpl_id', $cpls->pluck('id'))
            ->get(['cpl_id', 'level'])
            ->keyBy('cpl_id');

        $nilaiPerCpl = $cpls->mapWithKeys(fn($cpl) => [
            $cpl->id => match ($mkCplRows[$cpl->id]->level ?? null) {
                'Tinggi' => 5, 'Sedang' => 3, 'Rendah' => 1, default => 0,
            },
        ]);
        $mkRowTotal = $nilaiPerCpl->sum();
        // Gunakan Largest Remainder Method (sama dengan Tabel 5) agar integer & total = 100
        $tabel5 = $mkRowTotal > 0
            ? MatriksService::roundToIntegers($nilaiPerCpl->mapWithKeys(fn($nilai, $cplId) => [
                $cplId => $nilai / $mkRowTotal * 100,
              ])->toArray())
            : $nilaiPerCpl->mapWithKeys(fn($_, $cplId) => [$cplId => 0])->toArray();

        return view('kurikulum.cpmk.show', compact('mataKuliah', 'cpls', 'cpmks', 'cpmkCplMap', 'tabel5'));
    }

    /* ----------------------------------------------------------------
     * STORE — tambah CPMK baru (AJAX)
     * ---------------------------------------------------------------- */
    public function store(Request $request, MataKuliah $mataKuliah): JsonResponse
    {
        $data = $request->validate([
            'deskripsi' => 'required|string|max:1000',
        ]);

        $urutan = Cpmk::where('mata_kuliah_id', $mataKuliah->id)->max('urutan') + 1;
        $kode   = 'CPMK ' . $urutan;

        $cpmk = Cpmk::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'kode'           => $kode,
            'deskripsi'      => $data['deskripsi'],
            'urutan'         => $urutan,
        ]);

        return response()->json(['ok' => true, 'cpmk' => $cpmk]);
    }

    /* ----------------------------------------------------------------
     * UPDATE — edit deskripsi CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function update(Request $request, Cpmk $cpmk): JsonResponse
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $data = $request->validate([
            'deskripsi' => 'required|string|max:1000',
        ]);
        $cpmk->update($data);

        return response()->json(['ok' => true]);
    }

    /* ----------------------------------------------------------------
     * DESTROY — hapus CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function destroy(Cpmk $cpmk): JsonResponse
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $cpmk->delete();
        return response()->json(['ok' => true]);
    }

    /* ----------------------------------------------------------------
     * SAVE CPL RELATION — toggle centang V per CPMK × CPL (AJAX)
     * ---------------------------------------------------------------- */
    public function saveCplRelation(Request $request, Cpmk $cpmk): JsonResponse
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $data = $request->validate([
            'cpl_id'       => 'required|exists:cpls,id',
            'has_relation' => 'required|boolean',
        ]);

        $updateData = ['has_relation' => $data['has_relation']];
        if (!$data['has_relation']) {
            $updateData['bobot_persen'] = 0;   // reset bobot hanya saat uncheck
        }

        CpmkCpl::updateOrCreate(
            ['cpmk_id' => $cpmk->id, 'cpl_id' => $data['cpl_id']],
            $updateData
        );

        // Hitung ulang total bobot (hanya CPL yang has_relation=true)
        $totalBobot = CpmkCpl::where('cpmk_id', $cpmk->id)
            ->where('has_relation', true)
            ->sum('bobot_persen');

        return response()->json(['ok' => true, 'total_bobot' => round($totalBobot, 2)]);
    }

    /* ----------------------------------------------------------------
     * SAVE BOBOT — simpan % bobot per CPMK × CPL (AJAX)
     * ---------------------------------------------------------------- */
    public function saveBobot(Request $request, Cpmk $cpmk): JsonResponse
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $data = $request->validate([
            'cpl_id' => 'required|exists:cpls,id',
            'bobot'  => 'required|numeric|min:0|max:100',
        ]);

        // Jangan ubah has_relation saat bobot dikosongkan —
        // has_relation hanya dikelola lewat endpoint cpl-relation.
        // Hanya set has_relation=true saat bobot baru diisi (baris baru).
        $updateData = ['bobot_persen' => $data['bobot']];
        if ($data['bobot'] > 0) {
            $updateData['has_relation'] = true;
        }

        CpmkCpl::updateOrCreate(
            ['cpmk_id' => $cpmk->id, 'cpl_id' => $data['cpl_id']],
            $updateData
        );

        // Hitung total bobot per CPMK (hanya CPL yang has_relation=true)
        $totalBobot = CpmkCpl::where('cpmk_id', $cpmk->id)
            ->where('has_relation', true)
            ->sum('bobot_persen');

        return response()->json(['ok' => true, 'total_bobot' => round($totalBobot, 2)]);
    }

    /* ----------------------------------------------------------------
     * TABEL 6 — Keterkaitan CPMK × CPL (semua MK, tanda V)
     * ---------------------------------------------------------------- */
    public function tabel6(Request $request)
    {
        $psId = $this->programStudiId();
        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks.cpmkCpls'])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->isNotEmpty());

        return view('kurikulum.cpmk.tabel6', compact('cpls', 'mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 7 — Bobot % CPMK × CPL (semua MK)
     * ---------------------------------------------------------------- */
    public function tabel7(Request $request)
    {
        $psId = $this->programStudiId();
        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks.cpmkCpls'])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->isNotEmpty());

        return view('kurikulum.cpmk.tabel7', compact('cpls', 'mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 11 — % Bobot Sub-CPMK per CPMK
     *   Bobot tiap Sub = total_bobot_CPMK / jumlah_sub_dalam_CPMK
     * ---------------------------------------------------------------- */
    public function tabel11()
    {
        $psId = $this->programStudiId();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks' => fn($q) => $q->with(['cpmkCpls', 'subCpmks.asesmen'])->orderBy('urutan')])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty());

        // Hitung distribusi bobot: setiap Sub-CPMK mendapat porsi rata dari total bobot CPMK-nya
        foreach ($mataKuliahs as $mk) {
            $maxSubs = $mk->cpmks->max(fn($c) => $c->subCpmks->count()) ?? 0;
            $mk->max_subs = $mk->cpmks->flatMap->subCpmks->count(); // total subs in MK
            foreach ($mk->cpmks as $cpmk) {
                $totalBobot   = $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
                $subCount     = $cpmk->subCpmks->count();
                $bobotPerSub  = $subCount > 0 ? round($totalBobot / $subCount, 2) : 0;
                $cpmk->total_bobot  = $totalBobot;
                $cpmk->bobot_per_sub = $bobotPerSub;
            }
        }

        return view('kurikulum.cpmk.tabel11', compact('mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 12 — % Matriks CPMK × CPL (bobot_persen + total per CPL)
     * ---------------------------------------------------------------- */
    public function tabel12()
    {
        $psId = $this->programStudiId();
        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks.cpmkCpls'])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->isNotEmpty() &&
            $mk->cpmks->some(fn($c) => $c->cpmkCpls->isNotEmpty()));

        return view('kurikulum.cpmk.tabel12', compact('cpls', 'mataKuliahs'));
    }
}
