<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserImportController extends Controller
{
    public function form()
    {
        return view('super-admin.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.mimes' => 'File harus berformat Excel (.xlsx/.xls) atau CSV.',
        ]);

        $import = new UserImport();
        Excel::import($import, $request->file('file'));

        $r = $import->result;
        $msg = "Import selesai — {$r['created']} akun dibuat, {$r['updated']} diperbarui.";

        return redirect()->route('super-admin.users.index')
            ->with('success', $msg)
            ->with('import_skipped', $r['skipped']);
    }

    /** Unduh template CSV. */
    public function template(): StreamedResponse
    {
        $filename = 'template-import-akun.csv';
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['nama', 'email', 'role', 'program_studi', 'password', 'nip']);
            fputcsv($out, ['Budi Santoso, S.E., M.M.', 'budi@unm.ac.id', 'dosen', 'MNJ-S1', '', '198001012010121001']);
            fputcsv($out, ['Siti Aminah, S.E., M.Si.', 'siti@unm.ac.id', 'kaprodi', 'MNJ-S1', '', '']);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
