<?php

namespace App\Http\Controllers;

use App\Models\Cpmk;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $psId = (int) $user->program_studi_id;

        if ($user->isDosen()) {
            return $this->dosenDashboard($user, $psId);
        }

        return $this->adminDashboard($psId);
    }

    /* ----------------------------------------------------------------
     * Dashboard Admin / Kaprodi
     * ---------------------------------------------------------------- */
    private function adminDashboard(int $psId)
    {
        $mataKuliahs = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with([
                'mkCpls'  => fn($q) => $q->whereNotNull('level'),
                'dosens:id,name,nip',
            ])
            ->withCount([
                'cpmks',
                'subCpmks',
                'subCpmks as sub_clo_count'     => fn($q) => $q->whereNotNull('kode_clo_tertinggi'),
                'subCpmks as sub_asesmen_count'  => fn($q) => $q->whereHas('asesmen'),
            ])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get()
            ->map(function ($mk) {
                $mk->has_matriks = $mk->mkCpls->isNotEmpty();
                $mk->score       = ($mk->has_matriks ? 1 : 0)
                                 + ($mk->cpmks_count > 0 ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 && $mk->sub_clo_count == $mk->sub_cpmks_count ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count ? 1 : 0);
                return $mk;
            });

        $total    = $mataKuliahs->count() ?: 1;
        $totSub   = $mataKuliahs->sum('sub_cpmks_count') ?: 1;

        $summary = [
            'total_mk'      => $mataKuliahs->count(),
            'mk_lengkap'    => $mataKuliahs->where('score', 5)->count(),
            'mk_pending'    => $mataKuliahs->where('score', '<', 5)->count(),
            'mk_matriks'    => $mataKuliahs->filter(fn($m) => $m->has_matriks)->count(),
            'mk_cpmk'       => $mataKuliahs->filter(fn($m) => $m->cpmks_count > 0)->count(),
            'mk_subcpmk'    => $mataKuliahs->filter(fn($m) => $m->sub_cpmks_count > 0)->count(),
            'total_sub'     => $mataKuliahs->sum('sub_cpmks_count'),
            'total_clo'     => $mataKuliahs->sum('sub_clo_count'),
            'total_asesmen' => $mataKuliahs->sum('sub_asesmen_count'),
            'pct_matriks'   => round($mataKuliahs->filter(fn($m) => $m->has_matriks)->count() / $total * 100),
            'pct_cpmk'      => round($mataKuliahs->filter(fn($m) => $m->cpmks_count > 0)->count() / $total * 100),
            'pct_subcpmk'   => round($mataKuliahs->filter(fn($m) => $m->sub_cpmks_count > 0)->count() / $total * 100),
            'pct_clo'       => round($mataKuliahs->sum('sub_clo_count') / $totSub * 100),
            'pct_asesmen'   => round($mataKuliahs->sum('sub_asesmen_count') / $totSub * 100),
        ];

        // MK belum selesai (score < 5), grouped by semester
        $mkPending = $mataKuliahs->where('score', '<', 5)->groupBy('semester');

        // Breakdown per semester
        $bySemester = $mataKuliahs->groupBy('semester')->map(fn($mks) => [
            'total'   => $mks->count(),
            'lengkap' => $mks->where('score', 5)->count(),
            'pct'     => $mks->count() ? round($mks->where('score', 5)->count() / $mks->count() * 100) : 0,
        ])->sortKeys();

        // Ringkasan per dosen: berapa MK yg ditugaskan & berapa yg selesai
        $dosens = User::role('dosen')
            ->where('program_studi_id', $psId)
            ->with(['mataKuliahs' => function ($q) use ($psId) {
                $q->where('program_studi_id', $psId)->where('aktif', true);
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($d) use ($mataKuliahs) {
                $ids           = $d->mataKuliahs->pluck('id');
                $mkDosen       = $mataKuliahs->whereIn('id', $ids);
                $d->total_mk   = $mkDosen->count();
                $d->mk_lengkap = $mkDosen->where('score', 5)->count();
                $d->mk_pending = $mkDosen->where('score', '<', 5)->count();
                return $d;
            });

        // Ringkasan asesmen per MK (hanya yang punya Sub-CPMK)
        $mkAsesmen = $mataKuliahs
            ->filter(fn($mk) => $mk->sub_cpmks_count > 0)
            ->sortBy(['semester', 'urutan'])
            ->values();

        return view('dashboard', compact('summary', 'mkPending', 'bySemester', 'dosens', 'mkAsesmen'));
    }

    /* ----------------------------------------------------------------
     * Halaman Status Kelengkapan semua MK
     * ---------------------------------------------------------------- */
    public function kelengkapan(Request $request)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);

        $psId     = (int) auth()->user()->program_studi_id;
        $semester = $request->input('semester');

        $query = MataKuliah::where('program_studi_id', $psId)
            ->where('aktif', true)
            ->with(['mkCpls' => fn($q) => $q->whereNotNull('level'), 'dosens:id,name'])
            ->withCount([
                'cpmks',
                'subCpmks',
                'subCpmks as sub_clo_count'    => fn($q) => $q->whereNotNull('kode_clo_tertinggi'),
                'subCpmks as sub_asesmen_count' => fn($q) => $q->whereHas('asesmen'),
            ])
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->orderBy('semester')->orderBy('urutan');

        $mataKuliahs = $query->get()->map(function ($mk) {
            $mk->has_matriks = $mk->mkCpls->isNotEmpty();
            $allClo  = $mk->sub_cpmks_count > 0 && $mk->sub_clo_count == $mk->sub_cpmks_count;
            $allAses = $mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count;
            $mk->score = ($mk->has_matriks ? 1 : 0)
                       + ($mk->cpmks_count > 0 ? 1 : 0)
                       + ($mk->sub_cpmks_count > 0 ? 1 : 0)
                       + ($allClo ? 1 : 0)
                       + ($allAses ? 1 : 0);
            $mk->checks = [
                'matriks'  => $mk->has_matriks,
                'cpmk'     => $mk->cpmks_count > 0,
                'subcpmk'  => $mk->sub_cpmks_count > 0,
                'clo'      => $allClo,
                'asesmen'  => $allAses,
            ];
            return $mk;
        });

        $grouped = $mataKuliahs->groupBy('semester')->sortKeys();

        return view('kelengkapan', compact('grouped', 'semester'));
    }

    /* ----------------------------------------------------------------
     * Dashboard Dosen
     * ---------------------------------------------------------------- */
    private function dosenDashboard(User $user, int $psId)
    {
        $mkIds = $user->mataKuliahs()->pluck('mata_kuliahs.id');

        $mataKuliahs = MataKuliah::whereIn('id', $mkIds)
            ->where('aktif', true)
            ->with(['mkCpls' => fn($q) => $q->whereNotNull('level')])
            ->withCount([
                'cpmks',
                'subCpmks',
                'subCpmks as sub_clo_count'     => fn($q) => $q->whereNotNull('kode_clo_tertinggi'),
                'subCpmks as sub_asesmen_count'  => fn($q) => $q->whereHas('asesmen'),
            ])
            ->orderBy('semester')
            ->orderBy('urutan')
            ->get()
            ->map(function ($mk) {
                $mk->has_matriks = $mk->mkCpls->isNotEmpty();
                $mk->score       = ($mk->has_matriks ? 1 : 0)
                                 + ($mk->cpmks_count > 0 ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 && $mk->sub_clo_count == $mk->sub_cpmks_count ? 1 : 0)
                                 + ($mk->sub_cpmks_count > 0 && $mk->sub_asesmen_count == $mk->sub_cpmks_count ? 1 : 0);
                return $mk;
            });

        $total = $mataKuliahs->count() ?: 1;

        $summary = [
            'total_mk'   => $mataKuliahs->count(),
            'mk_lengkap' => $mataKuliahs->where('score', 5)->count(),
            'mk_pending' => $mataKuliahs->where('score', '<', 5)->count(),
        ];

        // CPMK pertama (urutan terendah) per MK — untuk link langsung ke sub-cpmk.show
        $firstCpmkByMk = Cpmk::whereIn('mata_kuliah_id', $mataKuliahs->pluck('id'))
            ->orderBy('urutan')->orderBy('id')
            ->get(['id', 'mata_kuliah_id'])
            ->unique('mata_kuliah_id')
            ->keyBy('mata_kuliah_id');

        return view('dashboard-dosen', compact('summary', 'mataKuliahs', 'firstCpmkByMk'));
    }
}
