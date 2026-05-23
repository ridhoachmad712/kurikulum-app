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

    /** Halaman utama — portal informasi kurikulum */
    public function home()
    {
        $prodi = $this->getProdi();

        $cpls = Cpl::where('program_studi_id', $prodi->id)
            ->orderBy('urutan')
            ->withCount('mataKuliahs')
            ->get();

        $mataKuliahs = MataKuliah::where('program_studi_id', $prodi->id)
            ->where('aktif', true)
            ->withCount(['cpmks', 'subCpmks'])
            ->with([
                'mkCpls.cpl',
                'cpmks' => fn($q) => $q->orderBy('urutan'),
            ])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get();

        $stats = [
            'total_mk'   => $mataKuliahs->count(),
            'total_cpl'  => $cpls->count(),
            'total_sks'  => $mataKuliahs->sum('sks'),
            'total_cpmk' => $mataKuliahs->sum('cpmks_count'),
        ];

        $profilLulusans = ProfilLulusan::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();

        return view('public.home', compact('prodi', 'cpls', 'mataKuliahs', 'stats', 'profilLulusans'));
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

        // Build cpmkCplMap: [cpmk_id][cpl_id] = ['has_relation'=>bool, 'bobot'=>float]
        $cpmkCplMap = [];
        foreach ($mataKuliah->cpmks as $cpmk) {
            foreach ($cpmk->cpmkCpls as $cc) {
                $cpmkCplMap[$cpmk->id][$cc->cpl_id] = [
                    'has_relation' => (bool) $cc->has_relation,
                    'bobot'        => (float) $cc->bobot_persen,
                ];
            }
        }

        // CPL yang terkait dengan MK ini
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

        // Data untuk radar chart
        $chartLabels   = $cpls->pluck('kode');
        $chartMkData   = $cpls->pluck('mk_count');
        $chartCpmkData = $cpls->pluck('cpmk_count');

        return view('public.cpl', compact('prodi', 'cpls', 'chartLabels', 'chartMkData', 'chartCpmkData'));
    }

    /** Halaman Matriks MK–CPL */
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
