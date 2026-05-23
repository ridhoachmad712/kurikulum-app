<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\ProfilLulusan;
use App\Models\ProgramStudi;
use App\Services\MatriksService;

class PublicController extends Controller
{
    public function __construct(private MatriksService $service) {}

    private function getProdi(): ProgramStudi
    {
        return ProgramStudi::where('aktif', true)->firstOrFail();
    }

    /** Halaman utama — overview kurikulum */
    public function home()
    {
        $prodi = $this->getProdi();

        $cpls = Cpl::where('program_studi_id', $prodi->id)
            ->orderBy('urutan')
            ->withCount(['mataKuliahs as mk_count' => fn($q) => $q->where('aktif', true)])
            ->get();

        $mataKuliahs = MataKuliah::where('program_studi_id', $prodi->id)
            ->where('aktif', true)
            ->withCount('cpmks')
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        $stats = [
            'total_mk'   => $mataKuliahs->count(),
            'total_cpl'  => $cpls->count(),
            'total_sks'  => $mataKuliahs->sum('sks'),
            'total_cpmk' => $mataKuliahs->sum('cpmks_count'),
        ];

        // Distribusi per semester
        $bySemester = $mataKuliahs->groupBy('semester')->map(fn($g) => [
            'count' => $g->count(),
            'sks'   => $g->sum('sks'),
        ]);

        return view('public.home', compact('prodi', 'cpls', 'stats', 'bySemester'));
    }

    /** Halaman Profil Program Studi */
    public function profil()
    {
        $prodi = $this->getProdi();
        $profilLulusans = ProfilLulusan::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();
        return view('public.profil', compact('prodi', 'profilLulusans'));
    }

    /** Daftar semua mata kuliah */
    public function mataKuliah()
    {
        $prodi = $this->getProdi();

        $cpls = Cpl::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();

        $mataKuliahs = MataKuliah::where('program_studi_id', $prodi->id)
            ->where('aktif', true)
            ->withCount(['cpmks', 'subCpmks'])
            ->with(['mkCpls' => fn($q) => $q->whereNotNull('level'), 'dosens:id,name'])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get()
            ->groupBy('semester');

        return view('public.mata-kuliah', compact('prodi', 'cpls', 'mataKuliahs'));
    }

    /** Detail satu mata kuliah */
    public function mataKuliahShow(MataKuliah $mataKuliah)
    {
        $mataKuliah->load([
            'cpmks.cpmkCpls.cpl',
            'cpmks.subCpmks.asesmen',
            'mkCpls.cpl',
            'dosens:id,name,nip',
        ]);

        $cpls = Cpl::where('program_studi_id', $mataKuliah->program_studi_id)
            ->orderBy('urutan')->get();

        $cpmkCplMap = [];
        foreach ($mataKuliah->cpmks as $cpmk) {
            foreach ($cpmk->cpmkCpls as $cc) {
                $cpmkCplMap[$cpmk->id][$cc->cpl_id] = [
                    'has_relation' => (bool) $cc->has_relation,
                    'bobot'        => (float) $cc->bobot_persen,
                ];
            }
        }

        $relatedCplIds = $mataKuliah->mkCpls->pluck('cpl_id')->toArray();

        return view('public.mata-kuliah-show', compact('mataKuliah', 'cpls', 'cpmkCplMap', 'relatedCplIds'));
    }

    /** Halaman CPL */
    public function cpl()
    {
        $prodi = $this->getProdi();

        $cpls = Cpl::where('program_studi_id', $prodi->id)
            ->orderBy('urutan')
            ->withCount(['mataKuliahs as mk_count' => fn($q) => $q->where('aktif', true)])
            ->with([
                'mataKuliahs' => fn($q) => $q->where('aktif', true)->orderBy('semester')->orderBy('urutan'),
                'cpmkCpls'    => fn($q) => $q->where('has_relation', true),
            ])
            ->get()
            ->each(fn($cpl) => $cpl->cpmk_count = $cpl->cpmkCpls->count());

        $chartLabels   = $cpls->pluck('kode');
        $chartMkData   = $cpls->pluck('mk_count');
        $chartCpmkData = $cpls->pluck('cpmk_count');

        return view('public.cpl', compact('prodi', 'cpls', 'chartLabels', 'chartMkData', 'chartCpmkData'));
    }

    /** Halaman Kurikulum — 12 tabel (read-only) */
    public function kurikulum()
    {
        $prodi = $this->getProdi();
        $psId  = $prodi->id;

        $cpls = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();

        // Load MK sekali dengan semua relasi yang dibutuhkan 12 tabel
        $mataKuliahs = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'cpmks' => fn($q) => $q->with([
                    'cpmkCpls',
                    'subCpmks' => fn($q2) => $q2
                        ->with(['subCpmkClos.cloTaxonomi'])
                        ->orderBy('urutan'),
                ])->orderBy('urutan'),
            ])
            ->orderBy('urutan')
            ->get();

        // ── Tabel 1–5: MatriksService ───────────────────────────────
        $matriksInput       = $this->service->getMatriksInput($psId);
        $nilaiMatrix        = $this->service->getNilaiMatrix($matriksInput, $mataKuliahs, $cpls);
        $rowTotals          = $this->service->getRowTotals($nilaiMatrix, $cpls);
        $colTotals          = $this->service->getColTotals($nilaiMatrix, $mataKuliahs);
        $grandTotal         = $this->service->getGrandTotal($nilaiMatrix);
        $pctGlobal          = $this->service->getPctBobotGlobal($nilaiMatrix, $grandTotal);
        $pctPerMk           = $this->service->getPctBobotPerMk($nilaiMatrix, $rowTotals);

        // Binary (Tabel 2)
        $binary = [];
        $binaryColTotals = [];
        foreach ($mataKuliahs as $mk) {
            foreach ($cpls as $cpl) {
                $binary[$mk->id][$cpl->id] = ($nilaiMatrix[$mk->id][$cpl->id] ?? 0) > 0 ? 1 : 0;
            }
        }
        foreach ($cpls as $cpl) {
            $t = 0;
            foreach ($mataKuliahs as $mk) { $t += $binary[$mk->id][$cpl->id] ?? 0; }
            $binaryColTotals[$cpl->id] = $t;
        }

        // % Bobot Global col totals (Tabel 4)
        $pctGlobalColTotals = [];
        foreach ($cpls as $cpl) {
            $t = 0;
            foreach ($mataKuliahs as $mk) { $t += $pctGlobal[$mk->id][$cpl->id] ?? 0; }
            $pctGlobalColTotals[$cpl->id] = round($t, 2);
        }

        // ── Tabel 6–12: filter MK ──────────────────────────────────
        $mataKuliahsWithCpmk = $mataKuliahs->filter(fn($mk) => $mk->cpmks->isNotEmpty());
        $mataKuliahsWithSub  = $mataKuliahs->filter(
            fn($mk) => $mk->cpmks->flatMap->subCpmks->isNotEmpty()
        );

        // Tabel 11: hitung bobot_per_sub per CPMK
        foreach ($mataKuliahs as $mk) {
            foreach ($mk->cpmks as $cpmk) {
                $totalBobot       = $cpmk->cpmkCpls->sum('bobot_persen');
                $subCount         = $cpmk->subCpmks->count();
                $cpmk->total_bobot   = $totalBobot;
                $cpmk->bobot_per_sub = $subCount > 0 ? round($totalBobot / $subCount, 2) : 0;
            }
        }

        return view('public.kurikulum', compact(
            'prodi', 'cpls', 'mataKuliahs',
            'matriksInput', 'nilaiMatrix', 'rowTotals', 'colTotals', 'grandTotal',
            'binary', 'binaryColTotals',
            'pctGlobal', 'pctGlobalColTotals', 'pctPerMk',
            'mataKuliahsWithCpmk', 'mataKuliahsWithSub'
        ));
    }

    /** Halaman Matriks MK–CPL (legacy, masih diakses dari footer/bookmark) */
    public function matriks()
    {
        $prodi = $this->getProdi();
        $psId  = $prodi->id;

        $cpls        = Cpl::where('program_studi_id', $psId)->orderBy('urutan')->get();
        $mataKuliahs = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        $input       = $this->service->getMatriksInput($psId);
        $nilaiMatrix = $this->service->getNilaiMatrix($input, $mataKuliahs, $cpls);
        $rowTotals   = $this->service->getRowTotals($nilaiMatrix, $cpls);
        $colTotals   = $this->service->getColTotals($nilaiMatrix, $mataKuliahs);

        $mataKuliahsBySmt = $mataKuliahs->groupBy('semester');

        return view('public.matriks', compact(
            'prodi', 'cpls', 'mataKuliahs', 'mataKuliahsBySmt',
            'nilaiMatrix', 'rowTotals', 'colTotals'
        ));
    }
}
