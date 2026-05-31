<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
