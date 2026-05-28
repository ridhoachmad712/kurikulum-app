<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Services\MatriksService;
use Illuminate\Http\Request;

class MatriksController extends Controller
{
    use DosenMkScope;

    public function __construct(private MatriksService $service) {}

    private function getProgramStudiId(Request $request): int
    {
        return (int) $request->input('program_studi_id', auth()->user()->program_studi_id);
    }

    /** Tabel 1: Level Keterkaitan MK–CPL (view-only, tanpa edit) */
    public function tabel1(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        $cpls           = Cpl::where('program_studi_id', $programStudiId)->orderBy('urutan')->get();
        $q              = MataKuliah::where('program_studi_id', $programStudiId)
                            ->where('aktif', true)->orderBy('semester')->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs    = $q->get();
        $matriksInput   = $this->service->getMatriksInput($programStudiId);

        return view('kurikulum.matriks.tabel1', compact('cpls', 'mataKuliahs', 'matriksInput', 'programStudiId'));
    }

    /** Tabel Input: Level Keterkaitan MK–CPL */
    public function input(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        $cpls           = Cpl::where('program_studi_id', $programStudiId)->orderBy('urutan')->get();
        $q              = MataKuliah::where('program_studi_id', $programStudiId)
                            ->where('aktif', true)->orderBy('semester')->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs    = $q->get();
        $matriksInput   = $this->service->getMatriksInput($programStudiId);

        return view('kurikulum.matriks.input', compact('cpls', 'mataKuliahs', 'matriksInput', 'programStudiId'));
    }

    /** Tabel 2: Matriks Binary 0/1 */
    public function tabel2(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        [$cpls, $mataKuliahs, $nilaiMatrix, $rowTotals, $colTotals] = $this->loadKalkulasi($programStudiId);

        // Binary: ada nilai (>0) = 1, tidak ada = 0
        $binary = [];
        foreach ($nilaiMatrix as $mkId => $cplValues) {
            foreach ($cplValues as $cplId => $nilai) {
                $binary[$mkId][$cplId] = $nilai > 0 ? 1 : 0;
            }
        }
        $binaryColTotals = [];
        foreach ($cpls as $cpl) {
            $binaryColTotals[$cpl->id] = array_sum(array_column(array_map(fn($r) => [$cpl->id => $r[$cpl->id] ?? 0], $binary), $cpl->id));
        }
        // Hitung col totals binary manual
        $binaryColTotals = [];
        foreach ($cpls as $cpl) {
            $total = 0;
            foreach ($mataKuliahs as $mk) {
                $total += $binary[$mk->id][$cpl->id] ?? 0;
            }
            $binaryColTotals[$cpl->id] = $total;
        }

        return view('kurikulum.matriks.tabel2', compact('cpls', 'mataKuliahs', 'binary', 'binaryColTotals', 'programStudiId'));
    }

    /** Tabel 3: Matriks Nilai Numerik */
    public function tabel3(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        [$cpls, $mataKuliahs, $nilaiMatrix, $rowTotals, $colTotals] = $this->loadKalkulasi($programStudiId);
        $grandTotal = $this->service->getGrandTotal($nilaiMatrix);

        return view('kurikulum.matriks.tabel3', compact('cpls', 'mataKuliahs', 'nilaiMatrix', 'rowTotals', 'colTotals', 'grandTotal', 'programStudiId'));
    }

    /** Tabel 4: % Bobot CPL Global */
    public function tabel4(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        [$cpls, $mataKuliahs, $nilaiMatrix, $rowTotals] = $this->loadKalkulasi($programStudiId);
        $grandTotal = $this->service->getGrandTotal($nilaiMatrix);
        $pctGlobal  = $this->service->getPctBobotGlobal($nilaiMatrix, $grandTotal);

        $pctGlobalColTotals = [];
        foreach ($cpls as $cpl) {
            $total = 0;
            foreach ($mataKuliahs as $mk) {
                $total += $pctGlobal[$mk->id][$cpl->id] ?? 0;
            }
            $pctGlobalColTotals[$cpl->id] = round($total, 2);
        }

        return view('kurikulum.matriks.tabel4', compact(
            'cpls', 'mataKuliahs', 'pctGlobal', 'pctGlobalColTotals', 'grandTotal', 'programStudiId'
        ));
    }

    /** Tabel 5: % Bobot CPL per MK */
    public function tabel5(Request $request)
    {
        $programStudiId = $this->getProgramStudiId($request);
        [$cpls, $mataKuliahs, $nilaiMatrix, $rowTotals] = $this->loadKalkulasi($programStudiId);
        $pctPerMk = $this->service->getPctBobotPerMk($nilaiMatrix, $rowTotals);

        return view('kurikulum.matriks.tabel5', compact(
            'cpls', 'mataKuliahs', 'pctPerMk', 'rowTotals', 'programStudiId'
        ));
    }

    private function loadKalkulasi(int $programStudiId): array
    {
        $cpls        = Cpl::where('program_studi_id', $programStudiId)->orderBy('urutan')->get();
        $q           = MataKuliah::where('program_studi_id', $programStudiId)->where('aktif', true)->orderBy('semester')->orderBy('urutan');
        $this->applyDosenScope($q);
        $mataKuliahs = $q->get();
        $input       = $this->service->getMatriksInput($programStudiId);
        $nilaiMatrix = $this->service->getNilaiMatrix($input, $mataKuliahs, $cpls);
        $rowTotals   = $this->service->getRowTotals($nilaiMatrix, $cpls);
        $colTotals   = $this->service->getColTotals($nilaiMatrix, $mataKuliahs);

        return [$cpls, $mataKuliahs, $nilaiMatrix, $rowTotals, $colTotals];
    }
}
