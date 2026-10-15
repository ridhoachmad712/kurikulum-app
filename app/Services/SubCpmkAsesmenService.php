<?php

namespace App\Services;

use App\Support\Rounding;
use Illuminate\Support\Collection;

class SubCpmkAsesmenService
{
    /**
     * Hitung matriks asesmen dari sumber CPMK. Nilai pivot yang kosong memakai
     * bobot otomatis (total bobot CPMK / jumlah Sub-CPMK), seperti Tabel 11.
     * Bobot efektif dinormalisasi agar seluruh asesmen CPMK tetap terdistribusi.
     *
     * @return array{rows: array<int, array<string, mixed>>, shares: array<int, array<int, array{sub_id:int, share:float}>>}
     */
    public function calculate(Collection $cpmks, array $asesmen, array $meta, array $fields): array
    {
        $rows = [];
        $shares = [];

        foreach ($cpmks as $cpmk) {
            $subs = $cpmk->subCpmks;
            $count = $subs->count();
            if ($count === 0) {
                continue;
            }

            $auto = (float) ($meta[$cpmk->id]['total_bobot'] ?? 0) / $count;
            $weights = [];
            foreach ($subs as $sub) {
                $weights[$sub->id] = $sub->pivot->bobot !== null
                    ? max(0, (float) $sub->pivot->bobot)
                    : $auto;

                if (! isset($rows[$sub->id])) {
                    $rows[$sub->id] = array_merge([
                        'id' => $sub->id,
                        'kode' => $sub->kode,
                        'deskripsi' => $sub->deskripsi,
                        'urutan' => $sub->urutan,
                        'cpmks' => [],
                        'target' => 0.0,
                        'total' => 0.0,
                    ], array_fill_keys($fields, 0.0));
                }
                $rows[$sub->id]['cpmks'][$cpmk->id] = $cpmk->kode;
            }

            $weightTotal = array_sum($weights);
            $shares[$cpmk->id] = [];
            foreach ($weights as $subId => $weight) {
                $shares[$cpmk->id][] = [
                    'sub_id' => (int) $subId,
                    'share' => $weightTotal > 0 ? $weight / $weightTotal : 1 / $count,
                ];
            }

            foreach (array_merge($fields, ['target']) as $field) {
                $amount = $field === 'target'
                    ? (float) ($meta[$cpmk->id]['target'] ?? 0)
                    : (float) ($asesmen[$cpmk->id][$field] ?? 0);
                $cents = (int) round($amount * 100);
                $raw = [];
                foreach ($shares[$cpmk->id] as $share) {
                    $raw[$share['sub_id']] = $cents * $share['share'];
                }
                foreach (Rounding::largestRemainder($raw, $cents) as $subId => $value) {
                    $rows[$subId][$field] += $value / 100;
                }
            }
        }

        foreach ($rows as &$row) {
            $row['total'] = round(array_sum(array_map(fn ($field) => $row[$field], $fields)), 2);
            $row['target'] = round($row['target'], 2);
            $row['cpmks'] = array_values($row['cpmks']);
        }
        unset($row);

        uasort($rows, fn ($a, $b) => ($a['urutan'] <=> $b['urutan']) ?: ($a['id'] <=> $b['id']));

        return ['rows' => $rows, 'shares' => $shares];
    }
}
