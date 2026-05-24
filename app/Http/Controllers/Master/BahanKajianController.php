<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\BahanKajian;
use App\Models\Cpl;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanKajianController extends Controller
{
    private function psId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    public function index()
    {
        $psId  = $this->psId();
        $items = BahanKajian::where('program_studi_id', $psId)
            ->withCount([
                'cpls as cpl_count' => fn($q) => $q->wherePivot('has_relation', true),
                'mataKuliahs as mk_count' => fn($q) => $q->wherePivot('has_relation', true),
            ])
            ->orderBy('urutan')
            ->get();

        return view('master.bahan-kajian.index', compact('items'));
    }

    public function create()
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        return view('master.bahan-kajian.form', ['item' => new BahanKajian]);
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:0',
        ]);
        $data['program_studi_id'] = $this->psId();
        BahanKajian::create($data);
        return redirect()->route('master.bahan-kajian.index')
            ->with('success', 'Bahan Kajian berhasil ditambahkan.');
    }

    public function edit(BahanKajian $bahanKajian)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        return view('master.bahan-kajian.form', ['item' => $bahanKajian]);
    }

    public function update(Request $request, BahanKajian $bahanKajian)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'kode'      => 'required|string|max:20',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:0',
        ]);
        $bahanKajian->update($data);
        return redirect()->route('master.bahan-kajian.index')
            ->with('success', 'Bahan Kajian berhasil diperbarui.');
    }

    public function destroy(BahanKajian $bahanKajian)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $bahanKajian->delete();
        return back()->with('success', 'Bahan Kajian dihapus.');
    }

    /* ── Matriks ── */

    public function matriks()
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $psId = $this->psId();

        $bks  = BahanKajian::where('program_studi_id', $psId)->orderBy('urutan')->get();
        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();
        $mks  = MataKuliah::where('program_studi_id', $psId)
                    ->where('aktif', true)
                    ->orderBy('semester')->orderBy('urutan')
                    ->get();

        $bkIds  = $bks->pluck('id');
        $cplIds = $cpls->pluck('id');
        $mkIds  = $mks->pluck('id');

        $cplBkSet = DB::table('cpl_bahan_kajian')
            ->whereIn('cpl_id', $cplIds)
            ->whereIn('bahan_kajian_id', $bkIds)
            ->where('has_relation', true)
            ->get()
            ->mapWithKeys(fn($r) => ["{$r->cpl_id}_{$r->bahan_kajian_id}" => true])
            ->toArray();

        $mkBkSet = DB::table('mk_bahan_kajian')
            ->whereIn('mata_kuliah_id', $mkIds)
            ->whereIn('bahan_kajian_id', $bkIds)
            ->where('has_relation', true)
            ->get()
            ->mapWithKeys(fn($r) => ["{$r->mata_kuliah_id}_{$r->bahan_kajian_id}" => true])
            ->toArray();

        $mkBySemester = $mks->groupBy('semester');

        return view('master.bahan-kajian.matriks',
            compact('bks', 'cpls', 'mks', 'mkBySemester', 'cplBkSet', 'mkBkSet'));
    }

    public function simpanMatriks(Request $request)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $psId = $this->psId();

        $bkIds  = BahanKajian::where('program_studi_id', $psId)->pluck('id');
        $cplIds = Cpl::where('program_studi_id', $psId)->pluck('id');
        $mkIds  = MataKuliah::where('program_studi_id', $psId)->where('aktif', true)->pluck('id');

        $checkedCpl = $request->input('cpl_bk', []);
        $checkedMk  = $request->input('mk_bk', []);

        // Upsert CPL × BK
        foreach ($cplIds as $cplId) {
            foreach ($bkIds as $bkId) {
                DB::table('cpl_bahan_kajian')->updateOrInsert(
                    ['cpl_id' => $cplId, 'bahan_kajian_id' => $bkId],
                    [
                        'has_relation' => in_array("{$cplId}_{$bkId}", $checkedCpl) ? 1 : 0,
                        'updated_at'   => now(),
                        'created_at'   => now(),
                    ]
                );
            }
        }

        // Upsert MK × BK
        foreach ($mkIds as $mkId) {
            foreach ($bkIds as $bkId) {
                DB::table('mk_bahan_kajian')->updateOrInsert(
                    ['mata_kuliah_id' => $mkId, 'bahan_kajian_id' => $bkId],
                    [
                        'has_relation' => in_array("{$mkId}_{$bkId}", $checkedMk) ? 1 : 0,
                        'updated_at'   => now(),
                        'created_at'   => now(),
                    ]
                );
            }
        }

        return back()->with('success', 'Matriks Bahan Kajian berhasil disimpan.');
    }
}
