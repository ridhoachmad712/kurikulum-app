<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BahanKajian;
use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use App\Models\Setting;
use App\Models\SubCpmk;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class SystemInfoController extends Controller
{
    public function index()
    {
        // ── Aplikasi & server ──────────────────────────────────────
        $app = [
            'php'     => PHP_VERSION,
            'laravel' => app()->version(),
            'server'  => request()->server('SERVER_SOFTWARE') ?: 'CLI / tidak diketahui',
            'os'      => php_uname('s') . ' ' . php_uname('r'),
            'timezone'=> config('app.timezone'),
            'env'     => app()->environment(),
            'debug'   => config('app.debug') ? 'Aktif' : 'Nonaktif',
        ];

        // ── Database ───────────────────────────────────────────────
        $dbName = DB::getDatabaseName();
        $dbSize = 0;
        try {
            $row = DB::selectOne(
                'SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?',
                [$dbName]
            );
            $dbSize = (int) ($row->size ?? 0);
        } catch (Throwable $e) {
            $dbSize = 0;
        }
        $db = [
            'driver' => config('database.default'),
            'name'   => $dbName,
            'size'   => $dbSize,
        ];

        // ── Disk ───────────────────────────────────────────────────
        $disk = [];
        try {
            $free  = disk_free_space(base_path());
            $total = disk_total_space(base_path());
            $disk = [
                'free'  => $free,
                'total' => $total,
                'used'  => $total - $free,
                'pct'   => $total > 0 ? round(($total - $free) / $total * 100) : 0,
            ];
        } catch (Throwable $e) {
            $disk = null;
        }

        // ── Jumlah record ──────────────────────────────────────────
        $counts = [
            'Pengguna'      => User::count(),
            'Mata Kuliah'   => MataKuliah::count(),
            'CPL'           => Cpl::count(),
            'CPMK'          => Cpmk::count(),
            'Sub-CPMK'      => SubCpmk::count(),
            'Bahan Kajian'  => BahanKajian::count(),
            'Log Aktivitas' => ActivityLog::count(),
        ];

        // ── Backup terakhir ────────────────────────────────────────
        $lastBackup = Setting::get('last_backup_at');

        return view('super-admin.system.index', compact('app', 'db', 'disk', 'counts', 'lastBackup'));
    }
}
