<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ErrorLogController extends Controller
{
    private function logPath(): string
    {
        return storage_path('logs/laravel.log');
    }

    public function index(Request $request)
    {
        $path  = $this->logPath();
        $level = strtolower((string) $request->input('level', ''));

        $entries = [];
        $size    = 0;

        if (File::exists($path)) {
            $size = File::size($path);
            $maxBytes = 512 * 1024; // baca maks 500 KB terakhir
            $content  = $size > $maxBytes ? $this->tail($path, $maxBytes) : File::get($path);

            $entries = $this->parse($content);

            if ($level !== '') {
                $entries = array_values(array_filter($entries, fn($e) => strtolower($e['level']) === $level));
            }

            $entries = array_slice(array_reverse($entries), 0, 200); // terbaru di atas, batasi 200
        }

        return view('super-admin.logs-error.index', [
            'entries' => $entries,
            'level'   => $level,
            'size'    => $size,
            'exists'  => File::exists($path),
        ]);
    }

    public function clear()
    {
        $path = $this->logPath();
        if (File::exists($path)) {
            File::put($path, '');
        }
        return back()->with('success', 'Log error berhasil dikosongkan. 🧹');
    }

    /** Ambil N byte terakhir dari file. */
    private function tail(string $path, int $bytes): string
    {
        $f = fopen($path, 'rb');
        $size = fstat($f)['size'];
        fseek($f, max(0, $size - $bytes));
        $data = fread($f, $bytes);
        fclose($f);
        $pos = strpos($data, "\n[");
        return $pos !== false ? substr($data, $pos + 1) : $data;
    }

    /** Parse isi log Laravel menjadi entri terstruktur. */
    private function parse(string $content): array
    {
        $entries = [];
        $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+([\w-]+)\.(\w+):\s*(.*)$/m';

        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        for ($i = 0; $i < count($matches); $i++) {
            $m     = $matches[$i];
            $start = $m[0][1];
            $end   = $matches[$i + 1][0][1] ?? strlen($content);
            $full  = substr($content, $start, $end - $start);

            $trace = trim(substr($full, strlen($m[0][0])));

            $entries[] = [
                'time'    => $m[1][0],
                'env'     => $m[2][0],
                'level'   => $m[3][0],
                'message' => trim($m[4][0]),
                'trace'   => $trace,
            ];
        }

        return $entries;
    }
}
