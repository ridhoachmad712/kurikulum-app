<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\CloTaxonomi;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use App\Models\SubCpmk;
use App\Models\SubCpmkClo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubCpmkController extends Controller
{
    use DosenMkScope;

    private function programStudiId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    /* ----------------------------------------------------------------
     * INDEX — daftar MK + jumlah Sub-CPMK per MK
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');
        $search   = $request->input('search');

        $query = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->withCount('subCpmks')
            ->with(['cpmks' => fn($q) => $q->withCount('subCpmks')->orderBy('urutan')])
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('urutan');

        if ($semester) $query->where('semester', $semester);
        $this->applyDosenScope($query);

        $mataKuliahs = $query->get()->filter(fn($mk) => $mk->cpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.index', compact('mataKuliahs', 'semester'));
    }

    /* ----------------------------------------------------------------
     * SHOW — kelola Sub-CPMK untuk 1 MK (semua CPMK)
     * ---------------------------------------------------------------- */
    public function show(MataKuliah $mataKuliah)
    {
        $this->authorizeDosenMk($mataKuliah);

        $subCpmks = SubCpmk::where('mata_kuliah_id', $mataKuliah->id)
            ->with(['subCpmkClos.cloTaxonomi', 'cpmks'])
            ->orderBy('urutan')
            ->get();

        $cpmks = Cpmk::where('mata_kuliah_id', $mataKuliah->id)
            ->with(['cpmkCpls', 'subCpmks'])
            ->orderBy('urutan')
            ->get();

        $clos = CloTaxonomi::orderBy('domain')->orderBy('level')->get()->groupBy('domain');

        // [sub_cpmk_id][cpmk_id] => bobot|null
        $cpmkConnections = [];
        foreach ($subCpmks as $sc) {
            $cpmkConnections[$sc->id] = [];
            foreach ($sc->cpmks as $c) {
                $cpmkConnections[$sc->id][$c->id] = $c->pivot->bobot;
            }
        }

        // Meta per CPMK: total bobot & bobot otomatis per Sub-CPMK
        // [cpmk_id] => ['total_bobot' => x, 'sub_count' => n, 'bobot_per_sub' => x/n]
        $cpmkMeta = [];
        foreach ($cpmks as $cpmk) {
            $totalBobot = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
            $subCount   = $cpmk->subCpmks->count();
            $cpmkMeta[$cpmk->id] = [
                'total_bobot'   => $totalBobot,
                'sub_count'     => $subCount,
                'bobot_per_sub' => $subCount > 0 ? round($totalBobot / $subCount, 2) : 0,
            ];
        }

        return view('kurikulum.sub-cpmk.show',
            compact('mataKuliah', 'subCpmks', 'cpmks', 'clos', 'cpmkConnections', 'cpmkMeta'));
    }

    /* ----------------------------------------------------------------
     * STORE — tambah Sub-CPMK baru di level MK (AJAX)
     * ---------------------------------------------------------------- */
    public function store(Request $request, MataKuliah $mataKuliah): JsonResponse
    {
        $this->authorizeDosenMk($mataKuliah);

        $data = $request->validate([
            'deskripsi'  => 'required|string|max:1000',
            'cpmk_ids'   => 'nullable|array',
            'cpmk_ids.*' => 'exists:cpmks,id',
        ]);

        $urutan  = SubCpmk::where('mata_kuliah_id', $mataKuliah->id)->max('urutan') + 1;
        $kode    = 'Sub-CPMK ' . $urutan;

        $subCpmk = SubCpmk::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'kode'           => $kode,
            'deskripsi'      => $data['deskripsi'],
            'urutan'         => $urutan,
        ]);

        if (!empty($data['cpmk_ids'])) {
            $subCpmk->cpmks()->attach($data['cpmk_ids']);
        }

        return response()->json(['ok' => true, 'sub_cpmk' => $subCpmk]);
    }

    /* ----------------------------------------------------------------
     * UPDATE — edit deskripsi Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function update(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate(['deskripsi' => 'required|string|max:1000']);
        $subCpmk->update($data);

        return response()->json(['ok' => true]);
    }

    /* ----------------------------------------------------------------
     * DESTROY — hapus Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function destroy(SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $subCpmk->delete();
        return response()->json(['ok' => true]);
    }

    /* ----------------------------------------------------------------
     * SAVE CPMK RELATION — toggle koneksi Sub-CPMK ↔ CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function saveCpmkRelation(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate([
            'cpmk_id'      => 'required|exists:cpmks,id',
            'has_relation' => 'required|boolean',
        ]);

        if ($data['has_relation']) {
            $subCpmk->cpmks()->syncWithoutDetaching([$data['cpmk_id']]);
        } else {
            $subCpmk->cpmks()->detach($data['cpmk_id']);
        }

        return response()->json(['ok' => true]);
    }

    /* ----------------------------------------------------------------
     * SAVE CLO — simpan CLO Tertinggi untuk Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function saveClo(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate(['clo_id' => 'nullable|exists:clo_taxonomis,id']);

        if ($data['clo_id']) {
            $clo    = CloTaxonomi::find($data['clo_id']);
            $domain = $clo->domain;

            $alreadySelected = SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
                ->where('clo_id', $data['clo_id'])->exists();

            if ($alreadySelected) {
                SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
                    ->where('clo_id', $data['clo_id'])->delete();
            } else {
                SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
                    ->whereHas('cloTaxonomi', fn($q) => $q->where('domain', $domain))
                    ->delete();

                SubCpmkClo::create([
                    'sub_cpmk_id'  => $subCpmk->id,
                    'clo_id'       => $data['clo_id'],
                    'is_tertinggi' => true,
                ]);
            }
        } else {
            SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)->delete();
        }

        $selectedClos = SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
            ->with('cloTaxonomi')->get()
            ->sortBy(fn($sc) => $sc->cloTaxonomi->domain);

        $kodeBadge   = $selectedClos->map(fn($sc) => $sc->cloTaxonomi->kode)->implode(', ');
        $selectedIds = $selectedClos->pluck('clo_id')->values()->toArray();

        $subCpmk->update(['kode_clo_tertinggi' => $kodeBadge ?: null]);

        return response()->json([
            'ok'             => true,
            'kode_clo_badge' => $kodeBadge ?: null,
            'selected_ids'   => $selectedIds,
        ]);
    }

    /* ----------------------------------------------------------------
     * SAVE BOBOT — simpan bobot per CPMK-SubCPMK pada pivot (AJAX)
     * ---------------------------------------------------------------- */
    public function saveBobot(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate([
            'cpmk_id' => 'required|exists:cpmks,id',
            'bobot'   => 'required|numeric|min:0|max:100',
        ]);

        $bobot = $data['bobot'] > 0 ? $data['bobot'] : null;
        $subCpmk->cpmks()->updateExistingPivot($data['cpmk_id'], ['bobot' => $bobot]);

        // Hitung total bobot semua Sub-CPMK dalam CPMK ini (dari pivot)
        $cpmk          = Cpmk::with(['cpmkCpls', 'subCpmks'])->find($data['cpmk_id']);
        $totalCpmk     = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
        $totalSubBobot = $cpmk->subCpmks->sum(function ($s) use ($subCpmk, $data) {
            return (float) ($s->id === $subCpmk->id
                ? $data['bobot']
                : ($s->pivot->bobot ?? 0));
        });

        return response()->json([
            'ok'              => true,
            'total_sub_bobot' => round($totalSubBobot, 2),
            'total_cpmk'      => round($totalCpmk, 2),
            'sisa'            => round($totalCpmk - $totalSubBobot, 2),
        ]);
    }

    /* ----------------------------------------------------------------
     * TABEL 9 — Matriks Keterkaitan CPMK × Sub-CPMK
     * ---------------------------------------------------------------- */
    public function tabel9()
    {
        $psId = $this->programStudiId();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'cpmks'    => fn($q) => $q->with('subCpmks')->orderBy('urutan'),
                'subCpmks' => fn($q) => $q->orderBy('urutan'),
            ])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel9', compact('mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 10 — Matriks Sub-CPMK × CPL
     * ---------------------------------------------------------------- */
    public function tabel10()
    {
        $psId = $this->programStudiId();
        $cpls = \App\Models\Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'cpmks'    => fn($q) => $q->with(['cpmkCpls', 'subCpmks'])->orderBy('urutan'),
                'subCpmks' => fn($q) => $q->orderBy('urutan'),
            ])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel10', compact('cpls', 'mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 8 — Rekapitulasi Sub-CPMK + CLO Tertinggi
     * ---------------------------------------------------------------- */
    public function tabel8(Request $request)
    {
        $psId    = $this->programStudiId();
        $domains = CloTaxonomi::distinct()->orderBy('domain')->pluck('domain');

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'subCpmks' => fn($q) => $q->with(['subCpmkClos.cloTaxonomi', 'cpmks'])->orderBy('urutan'),
            ])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel8', compact('mataKuliahs', 'domains'));
    }
}
