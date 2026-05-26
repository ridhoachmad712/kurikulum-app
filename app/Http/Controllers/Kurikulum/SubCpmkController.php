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
     * INDEX — daftar MK → CPMK dengan jumlah Sub-CPMK
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');

        $search = $request->input('search');

        $query = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
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
     * SHOW — kelola Sub-CPMK untuk 1 CPMK
     * ---------------------------------------------------------------- */
    public function show(Cpmk $cpmk)
    {
        $cpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($cpmk->mataKuliah);

        $cpmk->load(['mataKuliah', 'cpmkCpls.cpl']);

        $subCpmks = SubCpmk::where('cpmk_id', $cpmk->id)
            ->with(['subCpmkClos.cloTaxonomi'])
            ->orderBy('urutan')
            ->get();

        $clos = CloTaxonomi::orderBy('domain')->orderBy('level')->get()->groupBy('domain');

        return view('kurikulum.sub-cpmk.show', compact('cpmk', 'subCpmks', 'clos'));
    }

    /* ----------------------------------------------------------------
     * STORE — tambah Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function store(Request $request, Cpmk $cpmk): JsonResponse
    {
        $data = $request->validate([
            'deskripsi' => 'required|string|max:1000',
        ]);

        $urutan = SubCpmk::where('cpmk_id', $cpmk->id)->max('urutan') + 1;
        $kode   = 'Sub-CPMK ' . $cpmk->urutan . '.' . $urutan;

        $subCpmk = SubCpmk::create([
            'cpmk_id'        => $cpmk->id,
            'mata_kuliah_id' => $cpmk->mata_kuliah_id,
            'kode'           => $kode,
            'deskripsi'      => $data['deskripsi'],
            'urutan'         => $urutan,
        ]);

        return response()->json(['ok' => true, 'sub_cpmk' => $subCpmk]);
    }

    /* ----------------------------------------------------------------
     * UPDATE — edit deskripsi Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function update(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate([
            'deskripsi' => 'required|string|max:1000',
        ]);
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
     * SAVE CLO — simpan CLO Tertinggi untuk Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function saveClo(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing('mataKuliah');
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate([
            'clo_id' => 'nullable|exists:clo_taxonomis,id',
        ]);

        if ($data['clo_id']) {
            $clo    = CloTaxonomi::find($data['clo_id']);
            $domain = $clo->domain;

            // Toggle: jika CLO ini sudah dipilih → hapus (deselect)
            $alreadySelected = SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
                ->where('clo_id', $data['clo_id'])
                ->exists();

            if ($alreadySelected) {
                SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
                    ->where('clo_id', $data['clo_id'])
                    ->delete();
            } else {
                // Hapus CLO lama untuk domain yang sama (maks 1 per domain)
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
            // Hapus semua CLO
            SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)->delete();
        }

        // Reload dan susun ulang badge (semua domain terpilih)
        $selectedClos = SubCpmkClo::where('sub_cpmk_id', $subCpmk->id)
            ->with('cloTaxonomi')
            ->get()
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
     * SAVE BOBOT — simpan bobot manual per Sub-CPMK (AJAX)
     * ---------------------------------------------------------------- */
    public function saveBobot(Request $request, SubCpmk $subCpmk): JsonResponse
    {
        $subCpmk->loadMissing(['mataKuliah', 'cpmk.cpmkCpls', 'cpmk.subCpmks']);
        $this->authorizeDosenMk($subCpmk->mataKuliah);

        $data = $request->validate([
            'bobot' => 'required|numeric|min:0|max:100',
        ]);

        $subCpmk->update(['bobot' => $data['bobot'] > 0 ? $data['bobot'] : null]);

        // Hitung total bobot semua Sub-CPMK dalam CPMK ini
        $cpmk           = $subCpmk->cpmk;
        $totalCpmk      = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
        $totalSubBobot  = (float) $cpmk->subCpmks->sum(fn($s) => (float) ($s->id === $subCpmk->id ? $data['bobot'] : ($s->bobot ?? 0)));

        return response()->json([
            'ok'             => true,
            'total_sub_bobot'=> round($totalSubBobot, 2),
            'total_cpmk'     => round($totalCpmk, 2),
            'sisa'           => round($totalCpmk - $totalSubBobot, 2),
        ]);
    }

    /* ----------------------------------------------------------------
     * TABEL 9 — Matriks Keterkaitan CPMK × Sub-CPMK (tanda V)
     * ---------------------------------------------------------------- */
    public function tabel9()
    {
        $psId = $this->programStudiId();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks' => fn($q) => $q->with('subCpmks')->orderBy('urutan')])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel9', compact('mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 10 — Matriks Sub-CPMK × CPL (diwarisi dari CPMK)
     * ---------------------------------------------------------------- */
    public function tabel10()
    {
        $psId = $this->programStudiId();
        $cpls = \App\Models\Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'cpmks' => fn($q) => $q->with(['cpmkCpls', 'subCpmks'])->orderBy('urutan'),
            ])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel10', compact('cpls', 'mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * TABEL 8 — rekapitulasi Sub-CPMK + CLO Tertinggi
     * ---------------------------------------------------------------- */
    public function tabel8(Request $request)
    {
        $psId = $this->programStudiId();

        // Ambil semua domain CLO yang tersedia (urut abjad)
        $domains = CloTaxonomi::distinct()->orderBy('domain')->pluck('domain');

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'cpmks' => fn($q) => $q->with([
                    'subCpmks' => fn($q2) => $q2->with(['subCpmkClos.cloTaxonomi'])->orderBy('urutan'),
                ])->orderBy('urutan'),
            ])
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty());

        return view('kurikulum.sub-cpmk.tabel8', compact('mataKuliahs', 'domains'));
    }
}
