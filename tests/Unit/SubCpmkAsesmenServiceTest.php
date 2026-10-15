<?php

namespace Tests\Unit;

use App\Models\Cpmk;
use App\Models\SubCpmk;
use App\Services\SubCpmkAsesmenService;
use Illuminate\Database\Eloquent\Relations\Pivot;
use PHPUnit\Framework\TestCase;

class SubCpmkAsesmenServiceTest extends TestCase
{
    private function sub(int $id, int $order, ?float $weight): SubCpmk
    {
        $sub = new SubCpmk(['kode' => "Sub-CPMK {$id}", 'deskripsi' => "Sub {$id}", 'urutan' => $order]);
        $sub->id = $id;
        $sub->setRelation('pivot', new Pivot(['bobot' => $weight]));

        return $sub;
    }

    private function cpmk(int $id, array $subs): Cpmk
    {
        $cpmk = new Cpmk(['kode' => "CPMK {$id}"]);
        $cpmk->id = $id;
        $cpmk->setRelation('subCpmks', collect($subs));

        return $cpmk;
    }

    public function test_weighted_and_shared_sub_cpmk_preserve_assessment_totals(): void
    {
        $cpmks = collect([
            $this->cpmk(1, [$this->sub(1, 1, 30), $this->sub(2, 2, 70)]),
            $this->cpmk(2, [$this->sub(2, 2, null), $this->sub(3, 3, null)]),
        ]);

        $result = (new SubCpmkAsesmenService)->calculate($cpmks, [
            1 => ['tugas' => 10.01, 'uts' => 20],
            2 => ['tugas' => 5, 'uts' => 5],
        ], [
            1 => ['total_bobot' => 30, 'target' => 30],
            2 => ['total_bobot' => 10, 'target' => 10],
        ], ['tugas', 'uts']);

        $rows = $result['rows'];
        self::assertSame(3.0, $rows[1]['tugas']);
        self::assertSame(9.51, $rows[2]['tugas']);
        self::assertSame(2.5, $rows[3]['tugas']);
        self::assertEqualsWithDelta(15.01, array_sum(array_column($rows, 'tugas')), 0.001);
        self::assertEqualsWithDelta(25, array_sum(array_column($rows, 'uts')), 0.001);
        self::assertEqualsWithDelta(40, array_sum(array_column($rows, 'target')), 0.001);
        self::assertSame(['CPMK 1', 'CPMK 2'], $rows[2]['cpmks']);
    }

    public function test_zero_weights_fall_back_to_equal_shares(): void
    {
        $result = (new SubCpmkAsesmenService)->calculate(
            collect([$this->cpmk(1, [$this->sub(1, 1, null), $this->sub(2, 2, null)])]),
            [1 => ['tugas' => 5.01]],
            [1 => ['total_bobot' => 0, 'target' => 0]],
            ['tugas']
        );

        self::assertSame(2.51, $result['rows'][1]['tugas']);
        self::assertSame(2.5, $result['rows'][2]['tugas']);
    }
}
