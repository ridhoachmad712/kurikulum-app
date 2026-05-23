<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\MkCpl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatriksApiController extends Controller
{
    /**
     * Simpan satu sel (mata_kuliah × cpl) via fetch/AJAX.
     * level: Tinggi|Sedang|Rendah|'' (kosong = hapus relasi)
     */
    public function saveCell(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'cpl_id'         => 'required|exists:cpls,id',
            'level'          => 'nullable|in:Tinggi,Sedang,Rendah',
        ]);

        $level = $data['level'] ?: null;

        if ($level === null) {
            MkCpl::where('mata_kuliah_id', $data['mata_kuliah_id'])
                 ->where('cpl_id', $data['cpl_id'])
                 ->delete();
        } else {
            MkCpl::updateOrCreate(
                ['mata_kuliah_id' => $data['mata_kuliah_id'], 'cpl_id' => $data['cpl_id']],
                ['level' => $level]
            );
        }

        return response()->json(['ok' => true, 'level' => $level]);
    }

    /**
     * Bulk save: simpan semua sel sekaligus (untuk form submit tradisional)
     */
    public function bulkSave(Request $request): JsonResponse
    {
        $programStudiId = $request->input('program_studi_id', auth()->user()->program_studi_id);

        $cells = $request->validate([
            'cells'                  => 'required|array',
            'cells.*.mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'cells.*.cpl_id'         => 'required|exists:cpls,id',
            'cells.*.level'          => 'nullable|in:Tinggi,Sedang,Rendah',
        ])['cells'];

        foreach ($cells as $cell) {
            $level = $cell['level'] ?: null;
            if ($level === null) {
                MkCpl::where('mata_kuliah_id', $cell['mata_kuliah_id'])
                     ->where('cpl_id', $cell['cpl_id'])
                     ->delete();
            } else {
                MkCpl::updateOrCreate(
                    ['mata_kuliah_id' => $cell['mata_kuliah_id'], 'cpl_id' => $cell['cpl_id']],
                    ['level' => $level]
                );
            }
        }

        return response()->json(['ok' => true, 'saved' => count($cells)]);
    }
}
