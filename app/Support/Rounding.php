<?php

namespace App\Support;

class Rounding
{
    /**
     * Bulatkan sekumpulan nilai desimal menjadi bilangan bulat memakai
     * metode sisa terbesar (largest remainder method), sehingga JUMLAH
     * hasil pembulatan tepat sama dengan $target.
     *
     * Bila $target null, target = round(jumlah seluruh nilai).
     * Key array dipertahankan. Tie-break mengikuti urutan asli array
     * (PHP usort stabil sejak 8.0) agar identik dengan implementasi JS.
     *
     * Contoh: [10.4, 7.6] → target 18 → [10, 8]  (bukan 10 + 8 = 18 ✓)
     *
     * @param  array<int|string,float|int> $values
     * @return array<int|string,int>
     */
    public static function largestRemainder(array $values, ?int $target = null): array
    {
        if (empty($values)) {
            return [];
        }

        $target ??= (int) round(array_sum($values));

        $floors = [];
        $rema   = [];   // sisa desimal tiap nilai
        foreach ($values as $k => $v) {
            $f          = (int) floor($v);
            $floors[$k] = $f;
            $rema[$k]   = $v - $f;
        }

        $diff = $target - array_sum($floors);   // jumlah +1 yang harus dibagikan

        // Urutan key berdasarkan sisa desimal terbesar (stabil: tie → urutan asli)
        $order = array_keys($values);
        usort($order, fn ($a, $b) => $rema[$b] <=> $rema[$a]);

        if ($diff > 0) {
            for ($i = 0; $i < $diff && $i < count($order); $i++) {
                $floors[$order[$i]] += 1;
            }
        } elseif ($diff < 0) {
            // kurangi dari sisa terkecil (kasus langka)
            $rev = array_reverse($order);
            for ($i = 0; $i < -$diff && $i < count($rev); $i++) {
                $floors[$rev[$i]] -= 1;
            }
        }

        return $floors;
    }
}
