<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class PengukuranController extends Controller
{
    use DosenMkScope;

    private function programStudiId(): int
    {
        return (int) auth()->user()->program_studi_id;
    }

    /* ----------------------------------------------------------------
     * PENGUKURAN CPMK — index (daftar MK)
     * ---------------------------------------------------------------- */
    public function cpmk(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');
        $search   = $request->input('search');

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks' => fn($q) => $q->with(['cpmkCpls', 'subCpmks.asesmen'])->orderBy('urutan')])
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
                // Hitung bobot per Sub-CPMK untuk tiap CPMK (hanya CPL has_relation=true)
                foreach ($mk->cpmks as $cpmk) {
                    $totalBobot          = $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
                    $subCount            = $cpmk->subCpmks->count();
                    $cpmk->total_bobot   = $totalBobot;
                    $cpmk->bobot_per_sub = $subCount > 0 ? round($totalBobot / $subCount, 2) : 0;
                }

                // total_sub  = sub dari CPMK yang punya target valid (bobot_per_sub > 0)
                //              → denominator untuk progress selaras
                // filled_sub = semua sub yang sudah diisi asesmennya
                //              → dipakai badge "Sebagian"
                $total   = 0;
                $filled  = 0;
                $selaras = 0;

                foreach ($mk->cpmks as $cpmk) {
                    foreach ($cpmk->subCpmks as $sub) {
                        if ($cpmk->bobot_per_sub > 0) {
                            $total++;   // hanya hitung jika ada target nyata
                        }
                        if ($sub->asesmen !== null) {
                            $filled++;
                            if ($cpmk->bobot_per_sub > 0) {
                                $t = (float) $sub->asesmen->total;
                                if (abs($t - $cpmk->bobot_per_sub) <= 0.5) {
                                    $selaras++;
                                }
                            }
                        }
                    }
                }

                $mk->total_sub   = $total;
                $mk->filled_sub  = $filled;
                $mk->selaras_sub = $selaras;

                return $mk;
            });

        return view('kurikulum.pengukuran.cpmk-index', compact('mataKuliahs', 'semester', 'search'));
    }

    /* ----------------------------------------------------------------
     * PENGUKURAN CPMK — show (matriks input untuk 1 MK)
     * ---------------------------------------------------------------- */
    public function cpmkShow(MataKuliah $mataKuliah)
    {
        $this->authorizeDosenMk($mataKuliah);

        $cpmks = $mataKuliah->cpmks()
            ->with(['cpmkCpls', 'subCpmks.asesmen'])
            ->orderBy('urutan')
            ->get();

        foreach ($cpmks as $cpmk) {
            $totalBobot          = $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
            $subCount            = $cpmk->subCpmks->count();
            $cpmk->total_bobot   = $totalBobot;
            $cpmk->bobot_per_sub = $subCount > 0 ? round($totalBobot / $subCount, 2) : 0;
        }

        return view('kurikulum.pengukuran.cpmk-show', compact('mataKuliah', 'cpmks'));
    }

    /* ----------------------------------------------------------------
     * PENGUKURAN CPL — matriks MK × CPL
     * ---------------------------------------------------------------- */
    public function cpl(Request $request)
    {
        $psId     = $this->programStudiId();
        $semester = $request->input('semester');
        $search   = $request->input('search');

        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        $q = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['cpmks.cpmkCpls'])
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get()->filter(fn($mk) => $mk->cpmks->isNotEmpty());

        // cplMkScore[cpl_id][mk_id] = jumlah bobot_persen dari semua CPMK MK itu ke CPL ini
        $cplMkScore = [];
        $mkTotals   = [];   // mk_id => total bobot ke semua CPL

        foreach ($mataKuliahs as $mk) {
            $mkTotals[$mk->id] = 0;
            foreach ($mk->cpmks as $cpmk) {
                foreach ($cpmk->cpmkCpls as $cc) {
                    if ($cc->has_relation && $cc->bobot_persen > 0) {
                        $cplMkScore[$cc->cpl_id][$mk->id] =
                            ($cplMkScore[$cc->cpl_id][$mk->id] ?? 0) + (float) $cc->bobot_persen;
                        $mkTotals[$mk->id] += (float) $cc->bobot_persen;
                    }
                }
            }
        }

        // Total per CPL (across all MKs)
        $cplTotals = [];
        foreach ($cpls as $cpl) {
            $cplTotals[$cpl->id] = array_sum($cplMkScore[$cpl->id] ?? []);
        }

        $grandTotal = array_sum($cplTotals);

        return view('kurikulum.pengukuran.cpl',
            compact('cpls', 'mataKuliahs', 'cplMkScore', 'cplTotals', 'mkTotals', 'grandTotal', 'semester', 'search'));
    }
}
