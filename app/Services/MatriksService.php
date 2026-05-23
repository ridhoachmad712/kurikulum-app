<?php

namespace App\Services;

use App\Models\MataKuliah;
use App\Models\Cpl;
use App\Models\MkCpl;
use Illuminate\Support\Collection;

class MatriksService
{
    public function levelToNilai(?string $level): int
    {
        return match ($level) {
            'Tinggi' => 5,
            'Sedang' => 3,
            'Rendah' => 1,
            default  => 0,
        };
    }

    /**
     * Load semua mk_cpls untuk satu program studi, kembalikan sebagai
     * array asosiatif [mata_kuliah_id][cpl_id] = level|null
     */
    public function getMatriksInput(int $programStudiId): array
    {
        $rows = MkCpl::whereHas('mataKuliah', fn($q) => $q->where('program_studi_id', $programStudiId))
            ->get(['mata_kuliah_id', 'cpl_id', 'level']);

        $map = [];
        foreach ($rows as $row) {
            $map[$row->mata_kuliah_id][$row->cpl_id] = $row->level;
        }
        return $map;
    }

    /**
     * Tabel 3: [mk_id][cpl_id] = nilai numerik (0/1/3/5)
     */
    public function getNilaiMatrix(array $matriksInput, Collection $mataKuliahs, Collection $cpls): array
    {
        $result = [];
        foreach ($mataKuliahs as $mk) {
            foreach ($cpls as $cpl) {
                $level = $matriksInput[$mk->id][$cpl->id] ?? null;
                $result[$mk->id][$cpl->id] = $this->levelToNilai($level);
            }
        }
        return $result;
    }

    /**
     * Total nilai per baris (per MK, sum semua CPL)
     */
    public function getRowTotals(array $nilaiMatrix, Collection $cpls): array
    {
        $totals = [];
        foreach ($nilaiMatrix as $mkId => $cplValues) {
            $totals[$mkId] = array_sum($cplValues);
        }
        return $totals;
    }

    /**
     * Total nilai per kolom (per CPL, sum semua MK)
     */
    public function getColTotals(array $nilaiMatrix, Collection $mataKuliahs): array
    {
        $totals = [];
        foreach ($nilaiMatrix as $mkId => $cplValues) {
            foreach ($cplValues as $cplId => $nilai) {
                $totals[$cplId] = ($totals[$cplId] ?? 0) + $nilai;
            }
        }
        return $totals;
    }

    /**
     * Grand total semua nilai (dipakai sebagai pembagi Tabel 4)
     */
    public function getGrandTotal(array $nilaiMatrix): int
    {
        $total = 0;
        foreach ($nilaiMatrix as $cplValues) {
            $total += array_sum($cplValues);
        }
        return $total;
    }

    /**
     * Tabel 4: % Bobot CPL terhadap semua MK (global)
     * = nilai / grandTotal * 100
     */
    public function getPctBobotGlobal(array $nilaiMatrix, int $grandTotal): array
    {
        $result = [];
        foreach ($nilaiMatrix as $mkId => $cplValues) {
            foreach ($cplValues as $cplId => $nilai) {
                $result[$mkId][$cplId] = $grandTotal > 0
                    ? round($nilai / $grandTotal * 100, 2)
                    : 0;
            }
        }
        return $result;
    }

    /**
     * Tabel 5: % Bobot CPL dalam 1 MK
     * = nilai / rowTotal * 100
     */
    public function getPctBobotPerMk(array $nilaiMatrix, array $rowTotals): array
    {
        $result = [];
        foreach ($nilaiMatrix as $mkId => $cplValues) {
            $rowTotal = $rowTotals[$mkId] ?? 0;
            foreach ($cplValues as $cplId => $nilai) {
                $result[$mkId][$cplId] = $rowTotal > 0
                    ? round($nilai / $rowTotal * 100, 2)
                    : 0;
            }
        }
        return $result;
    }
}
