<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $action  = $request->input('action');
        $subject = $request->input('subject');
        $search  = $request->input('search');

        $logs = ActivityLog::with('user')
            ->when($action,  fn($q) => $q->where('action', $action))
            ->when($subject, fn($q) => $q->where('subject_type', $subject))
            ->when($search,  fn($q) => $q->where(fn($s) =>
                $s->where('subject_label', 'like', "%$search%")
                  ->orWhere('user_name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
            ))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('super-admin.logs.index', [
            'logs'     => $logs,
            'action'   => $action,
            'subject'  => $subject,
            'search'   => $search,
            'subjects' => ActivityLog::SUBJECT_LABELS,
        ]);
    }

    /** Bersihkan log lebih lama dari 90 hari. */
    public function prune()
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
        return back()->with('success', "{$deleted} log lama (>90 hari) dihapus.");
    }
}
