<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class CacheController extends Controller
{
    /** Peta tipe → perintah artisan + label. */
    private const COMMANDS = [
        'all'    => ['optimize:clear', 'Semua cache'],
        'config' => ['config:clear',   'Cache konfigurasi'],
        'view'   => ['view:clear',     'Cache tampilan (Blade)'],
        'route'  => ['route:clear',    'Cache rute'],
        'cache'  => ['cache:clear',    'Cache aplikasi'],
    ];

    public function clear(Request $request)
    {
        $type = $request->input('type', 'all');

        if (! isset(self::COMMANDS[$type])) {
            return back()->with('error', 'Jenis cache tidak dikenali.');
        }

        [$command, $label] = self::COMMANDS[$type];

        try {
            Artisan::call($command);
        } catch (Throwable $e) {
            return back()->with('error', "Gagal membersihkan {$label}: " . $e->getMessage());
        }

        return back()->with('success', "{$label} berhasil dibersihkan. 🧹");
    }
}
