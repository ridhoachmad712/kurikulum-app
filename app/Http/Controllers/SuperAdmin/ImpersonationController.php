<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /** Mulai login sebagai pengguna lain (hanya super admin). */
    public function start(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat masuk sebagai akun Anda sendiri.');
        }
        if ($user->hasRole('super_admin')) {
            return back()->with('error', 'Tidak dapat masuk sebagai sesama Super Admin.');
        }

        ActivityLog::record('impersonate', "masuk sebagai \"{$user->name}\"", 'Akun', $user->id, $user->name);

        // Simpan id super admin asli, lalu beralih
        session(['impersonator_id' => auth()->id()]);
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Anda sekarang masuk sebagai {$user->name}.");
    }

    /** Kembali ke akun super admin asli. */
    public function stop()
    {
        $impersonatorId = session('impersonator_id');
        if (! $impersonatorId) {
            return redirect()->route('dashboard');
        }

        Auth::loginUsingId($impersonatorId);
        session()->forget('impersonator_id');

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Anda telah kembali ke akun Super Admin.');
    }
}
