<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class UsageStatsController extends Controller
{
    public function index()
    {
        $since = now()->subDays(30)->startOfDay();

        $summary = [
            'login'        => ActivityLog::where('action', 'login')->where('created_at', '>=', $since)->count(),
            'unique_users' => ActivityLog::where('action', 'login')->where('created_at', '>=', $since)->distinct('user_id')->count('user_id'),
            'failed'       => ActivityLog::where('action', 'login_failed')->where('created_at', '>=', $since)->count(),
            'changes'      => ActivityLog::whereIn('action', ['created', 'updated', 'deleted'])->where('created_at', '>=', $since)->count(),
        ];

        // Tren login 14 hari
        $trend = [];
        for ($i = 13; $i >= 0; $i--) {
            $trend[now()->subDays($i)->toDateString()] = 0;
        }
        foreach (ActivityLog::where('action', 'login')->where('created_at', '>=', now()->subDays(13)->startOfDay())->get(['created_at']) as $l) {
            $d = $l->created_at->toDateString();
            if (isset($trend[$d])) $trend[$d]++;
        }

        // Pengguna paling aktif
        $topUsers = ActivityLog::select('user_name', DB::raw('COUNT(*) as total'))
            ->where('action', 'login')->where('created_at', '>=', $since)
            ->whereNotNull('user_name')
            ->groupBy('user_name')->orderByDesc('total')->limit(8)->get();

        // Jam sibuk
        $byHour = array_fill(0, 24, 0);
        foreach (ActivityLog::where('action', 'login')->get(['created_at']) as $h) {
            $byHour[(int) $h->created_at->format('G')]++;
        }

        // Distribusi aksi
        $byAction = ActivityLog::select('action', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $since)
            ->groupBy('action')->orderByDesc('total')->get();

        return view('super-admin.usage-stats.index', compact('summary', 'trend', 'topUsers', 'byHour', 'byAction'));
    }
}
