<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Audit autentikasi ──────────────────────────────────────
        Event::listen(Login::class, function (Login $event) {
            $this->safeLog(fn () => ActivityLog::record(
                'login',
                "{$event->user->name} masuk ke aplikasi",
                'Autentikasi', $event->user->getAuthIdentifier(), $event->user->name,
                $event->user->getAuthIdentifier(), $event->user->name,
            ));
        });

        Event::listen(Logout::class, function (Logout $event) {
            if (! $event->user) return;
            $this->safeLog(fn () => ActivityLog::record(
                'logout',
                "{$event->user->name} keluar dari aplikasi",
                'Autentikasi', $event->user->getAuthIdentifier(), $event->user->name,
                $event->user->getAuthIdentifier(), $event->user->name,
            ));
        });

        Event::listen(Failed::class, function (Failed $event) {
            $email = $event->credentials['email'] ?? '(tidak diketahui)';
            $this->safeLog(fn () => ActivityLog::record(
                'login_failed',
                "Percobaan login gagal untuk {$email}",
                'Autentikasi', null, $email,
                null, $email,
            ));
        });
    }

    /** Jalankan logging tanpa membuat error jika tabel belum ada. */
    private function safeLog(callable $fn): void
    {
        try { $fn(); } catch (Throwable $e) { /* abaikan */ }
    }
}
