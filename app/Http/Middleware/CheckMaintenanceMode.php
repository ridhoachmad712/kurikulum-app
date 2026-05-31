<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::bool('maintenance_mode')) {
            $user = $request->user();
            // Super admin tetap bisa akses; izinkan juga route login/logout agar bisa masuk
            $allowed = $request->routeIs('login') || $request->routeIs('logout');

            if (! $allowed && (! $user || ! $user->hasRole('super_admin'))) {
                return response()->view('maintenance', [
                    'appName' => Setting::get('app_name'),
                ], 503);
            }
        }

        return $next($request);
    }
}
