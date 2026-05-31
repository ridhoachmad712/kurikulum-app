<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class BackupController extends Controller
{
    /** Halaman backup — ringkasan DB + tombol export. */
    public function index()
    {
        $database = DB::getDatabaseName();
        $tables   = $this->tableNames();

        $rowCounts = [];
        $totalRows = 0;
        foreach ($tables as $t) {
            $c = DB::table($t)->count();
            $rowCounts[$t] = $c;
            $totalRows += $c;
        }
        arsort($rowCounts);

        return view('super-admin.backup.index', [
            'database'   => $database,
            'tableCount' => count($tables),
            'totalRows'  => $totalRows,
            'rowCounts'  => $rowCounts,
        ]);
    }

    /** Export seluruh database sebagai file .sql (PHP murni — portable). */
    public function download(): StreamedResponse
    {
        $tables   = $this->tableNames();
        $database = DB::getDatabaseName();
        $filename = 'backup-' . str($database)->slug() . '-' . now()->format('Ymd-His') . '.sql';

        \App\Models\Setting::put('last_backup_at', now()->toDateTimeString());

        return response()->streamDownload(function () use ($tables, $database) {
            $out = fopen('php://output', 'w');

            fwrite($out, "-- Backup database: {$database}\n");
            fwrite($out, '-- Dibuat: ' . now()->toDateTimeString() . "\n");
            fwrite($out, "-- Oleh: " . (auth()->user()->name ?? 'Super Admin') . "\n\n");
            fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $table) {
                // Struktur
                $create = DB::selectOne("SHOW CREATE TABLE `{$table}`");
                $createSql = $create->{'Create Table'} ?? null;
                if (!$createSql) continue;

                fwrite($out, "-- ----------------------------\n-- Tabel `{$table}`\n-- ----------------------------\n");
                fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");
                fwrite($out, $createSql . ";\n\n");

                // Data — chunk agar hemat memori
                DB::table($table)->orderBy((DB::getSchemaBuilder()->hasColumn($table, 'id') ? 'id' : DB::raw('1')))
                  ->chunk(500, function ($rows) use ($out, $table) {
                      foreach ($rows as $row) {
                          $cols = array_keys((array) $row);
                          $vals = array_map(function ($v) {
                              if (is_null($v)) return 'NULL';
                              return "'" . str_replace(["\\", "'", "\n", "\r"], ["\\\\", "\\'", "\\n", "\\r"], (string) $v) . "'";
                          }, array_values((array) $row));
                          $colList = '`' . implode('`, `', $cols) . '`';
                          fwrite($out, "INSERT INTO `{$table}` ({$colList}) VALUES (" . implode(', ', $vals) . ");\n");
                      }
                  });
                fwrite($out, "\n");
            }

            fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($out);
        }, $filename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /** Pulihkan database dari file .sql (DESTRUKTIF — menimpa data). */
    public function restore(Request $request)
    {
        $request->validate([
            'file'    => 'required|file|max:51200', // 50 MB
            'confirm' => 'accepted',
        ], [
            'confirm.accepted' => 'Anda harus mencentang konfirmasi sebelum memulihkan.',
        ]);

        $ext = strtolower($request->file('file')->getClientOriginalExtension());
        if ($ext !== 'sql') {
            return back()->with('error', 'File harus berformat .sql (hasil backup aplikasi ini).');
        }

        $sql = file_get_contents($request->file('file')->getRealPath());
        if (! $sql || ! preg_match('/CREATE TABLE|INSERT INTO/i', $sql)) {
            return back()->with('error', 'File tidak dikenali sebagai backup SQL yang valid.');
        }

        try {
            DB::unprepared("SET FOREIGN_KEY_CHECKS=0;\n" . $sql . "\nSET FOREIGN_KEY_CHECKS=1;");
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memulihkan: ' . $e->getMessage());
        }

        return redirect()->route('super-admin.backup.index')
            ->with('success', 'Database berhasil dipulihkan dari backup. Silakan login ulang jika diperlukan.');
    }

    /** Daftar nama tabel di database aktif. */
    private function tableNames(): array
    {
        return collect(DB::select('SHOW TABLES'))
            ->map(fn($row) => array_values((array) $row)[0])
            ->reject(fn($t) => in_array($t, ['migrations', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens']))
            ->values()
            ->all();
    }
}
