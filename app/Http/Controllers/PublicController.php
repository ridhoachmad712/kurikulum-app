<?php

namespace App\Http\Controllers;

use App\Models\BahanKajian;
use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\Peo;
use App\Models\ProfilLulusan;
use App\Models\ProgramStudi;
use App\Services\MatriksService;
use Illuminate\Support\Facades\Storage;

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
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        $peos = Peo::where('program_studi_id', $prodi->id)
            ->orderBy('urutan')->orderBy('id')->get();
        $concentrations = $mataKuliahs->pluck('konsentrasi')->filter()->unique()->values();

        $numKon = max(1, $concentrations->count());

        // Distribusi per semester — dihitung per 1 mahasiswa per jalur konsentrasi
        $bySemester = $mataKuliahs->groupBy('semester')->map(function ($g) use ($numKon) {
            $wajib      = $g->filter(fn($mk) => !$mk->konsentrasi && !$mk->is_pilihan);
            $konWajib   = $g->filter(fn($mk) =>  $mk->konsentrasi && !$mk->is_pilihan);
            $konPilihan = $g->filter(fn($mk) =>  $mk->is_pilihan);

            // Per mahasiswa: wajib global + bagian konsentrasi yang diambil
            $konWajibPerMhs     = $numKon ? (int) round($konWajib->count()   / $numKon) : 0;
            $konWajibSksPerMhs  = $numKon ? round($konWajib->sum('sks')  / $numKon)     : 0;
            $pilihanTaken       = $konPilihan->isNotEmpty() ? 3 : 0;           // mahasiswa ambil 3
            $pilihanAvgSks      = $konPilihan->isNotEmpty() ? $konPilihan->avg('sks') : 0;
            $pilihanSksPerMhs   = $pilihanTaken * $pilihanAvgSks;

            $effectiveCount = $wajib->count() + $konWajibPerMhs + $pilihanTaken;
            $effectiveSks   = (int) round($wajib->sum('sks') + $konWajibSksPerMhs + $pilihanSksPerMhs);

            return [
                'count'          => $g->count(),          // total di DB
                'effective_count'=> $effectiveCount,       // yang diambil 1 mahasiswa
                'sks'            => $g->sum('sks'),
                'effective_sks'  => $effectiveSks,
                'wajib_count'    => $wajib->count(),
                'kon_wajib'      => $konWajibPerMhs,
                'kon_pilihan'    => $pilihanTaken,
                'has_kon'        => $konWajib->isNotEmpty() || $konPilihan->isNotEmpty(),
                'has_pilihan'    => $konPilihan->isNotEmpty(),
            ];
        });

        $stats = [
            'total_cpl'   => $cpls->count(),
            'total_sks'   => $bySemester->sum('effective_sks'),
            'available_mk' => $mataKuliahs->count(),
            'available_sks' => $mataKuliahs->sum('sks'),
            'total_semesters' => $bySemester->count(),
            'total_concentrations' => $concentrations->count(),
        ];

        return view('public.home', compact('prodi', 'peos', 'cpls', 'stats', 'bySemester', 'concentrations'));
    }

    /** Halaman Profil Program Studi */
    public function profil()
    {
        $prodi = $this->getProdi();
        $profilLulusans = ProfilLulusan::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();
        return view('public.profil', compact('prodi', 'profilLulusans'));
    }

    /** Program Educational Objectives */
    public function peo()
    {
        $prodi = $this->getProdi();
        $peos = Peo::where('program_studi_id', $prodi->id)->orderBy('urutan')->orderBy('id')->get();

        return view('public.peo', compact('prodi', 'peos'));
    }

    /** Daftar semua mata kuliah */
    public function mataKuliah()
    {
        $prodi = $this->getProdi();

        $cpls = Cpl::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();

        $mataKuliahs = MataKuliah::where('program_studi_id', $prodi->id)
            ->where('aktif', true)
            ->with('rpsDocument')
            ->withCount(['cpmks', 'subCpmks'])
            ->with(['mkCpls' => fn($q) => $q->whereNotNull('level'), 'dosens:id,name'])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get()
            ->groupBy('semester');

        return view('public.mata-kuliah', compact('prodi', 'cpls', 'mataKuliahs'));
    }

    /** Peta semester interaktif dari mata kuliah aktif. */
    public function petaKurikulum()
    {
        $prodi = $this->getProdi();
        $mataKuliahs = MataKuliah::where('program_studi_id', $prodi->id)
            ->where('aktif', true)
            ->with(['mkCpls.cpl', 'dosens:id,name', 'rpsDocument'])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $cpls = Cpl::where('program_studi_id', $prodi->id)->orderBy('urutan')->get(['id', 'kode', 'deskripsi']);
        $concentrations = $mataKuliahs->pluck('konsentrasi')->filter()->unique()->sort()->values();
        $semesters = range(1, max(8, (int) $mataKuliahs->max('semester')));
        if ($mataKuliahs->contains(fn ($mk) => $mk->semester === null)) {
            $semesters[] = 0;
        }

        $courses = $mataKuliahs->map(fn ($mk) => [
            'id' => $mk->id,
            'code' => $mk->kode_rps,
            'name' => $mk->nama,
            'sks' => $mk->sks,
            'semester' => $mk->semester ?? 0,
            'concentration' => $mk->konsentrasi,
            'elective' => $mk->is_pilihan,
            'description' => $mk->deskripsi,
            'cpls' => $mk->mkCpls->filter(fn ($relation) => $relation->cpl && $relation->level !== null)
                ->map(fn ($relation) => [
                    'id' => $relation->cpl->id,
                    'code' => $relation->cpl->kode,
                    'level' => $relation->level,
                ])->values()->all(),
            'lecturers' => $mk->dosens->pluck('name')->all(),
            'hasRps' => $mk->rpsDocument !== null,
            'detailUrl' => route('public.mata-kuliah.show', $mk),
            'rpsUrl' => $mk->rpsDocument ? route('public.mata-kuliah.rps', $mk) : null,
        ])->values()->all();

        return view('public.peta-kurikulum', compact('prodi', 'courses', 'cpls', 'concentrations', 'semesters'));
    }

    /** Tampilkan PDF RPS yang diunggah dosen pengampu. */
    public function mataKuliahRps(MataKuliah $mataKuliah)
    {
        $prodi = $this->getProdi();
        abort_unless($mataKuliah->aktif && $mataKuliah->program_studi_id === $prodi->id, 404);

        $document = $mataKuliah->rpsDocument;
        abort_unless($document && Storage::disk('local')->exists($document->file_path), 404);

        $fileName = 'RPS-' . preg_replace('/[^A-Za-z0-9_-]/', '-', $mataKuliah->kode_rps) . '.pdf';

        return Storage::disk('local')->response($document->file_path, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
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
                        ->with(['subCpmkClos.cloTaxonomi', 'asesmen'])
                        ->orderBy('urutan'),
                ])->orderBy('urutan'),
            ])
            ->orderBy('semester')
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
                $totalBobot          = (float) $cpmk->cpmkCpls->where('has_relation', true)->sum('bobot_persen');
                $subCount            = $cpmk->subCpmks->count();
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

    /** Halaman Bahan Kajian — Tabel 6.2, 6.3, 6.4 */
    public function bahanKajian()
    {
        $prodi = $this->getProdi();
        $psId  = $prodi->id;

        // Bahan Kajian terurut
        $bahanKajians = BahanKajian::where('program_studi_id', $psId)
            ->orderBy('urutan')
            ->get();

        // CPL terurut
        $cpls = Cpl::where('program_studi_id', $psId)
            ->orderBy('urutan')
            ->get();

        // MK aktif terurut
        $mataKuliahs = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        // Build map: cpl_id → [bahan_kajian_id → has_relation]
        $cplBkMap = [];
        if ($bahanKajians->isNotEmpty() && $cpls->isNotEmpty()) {
            $cplBkRows = \DB::table('cpl_bahan_kajian')
                ->whereIn('cpl_id', $cpls->pluck('id'))
                ->whereIn('bahan_kajian_id', $bahanKajians->pluck('id'))
                ->get();
            foreach ($cplBkRows as $row) {
                $cplBkMap[$row->cpl_id][$row->bahan_kajian_id] = (bool) $row->has_relation;
            }
        }

        // Build map: mata_kuliah_id → [bahan_kajian_id → has_relation]
        $mkBkMap = [];
        if ($bahanKajians->isNotEmpty() && $mataKuliahs->isNotEmpty()) {
            $mkBkRows = \DB::table('mk_bahan_kajian')
                ->whereIn('mata_kuliah_id', $mataKuliahs->pluck('id'))
                ->whereIn('bahan_kajian_id', $bahanKajians->pluck('id'))
                ->get();
            foreach ($mkBkRows as $row) {
                $mkBkMap[$row->mata_kuliah_id][$row->bahan_kajian_id] = (bool) $row->has_relation;
            }
        }

        return view('public.bahan-kajian', compact(
            'prodi', 'bahanKajians', 'cpls', 'mataKuliahs', 'cplBkMap', 'mkBkMap'
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
