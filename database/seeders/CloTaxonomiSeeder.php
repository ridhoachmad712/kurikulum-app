<?php
namespace Database\Seeders;

use App\Models\CloTaxonomi;
use Illuminate\Database\Seeder;

class CloTaxonomiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode'=>'C1','domain'=>'Kognitif','level'=>1,'deskripsi'=>'Mengingat (Remember)'],
            ['kode'=>'C2','domain'=>'Kognitif','level'=>2,'deskripsi'=>'Memahami (Understand)'],
            ['kode'=>'C3','domain'=>'Kognitif','level'=>3,'deskripsi'=>'Menerapkan (Apply)'],
            ['kode'=>'C4','domain'=>'Kognitif','level'=>4,'deskripsi'=>'Menganalisis (Analyze)'],
            ['kode'=>'C5','domain'=>'Kognitif','level'=>5,'deskripsi'=>'Mengevaluasi (Evaluate)'],
            ['kode'=>'C6','domain'=>'Kognitif','level'=>6,'deskripsi'=>'Mencipta (Create)'],
            ['kode'=>'A1','domain'=>'Afektif','level'=>1,'deskripsi'=>'Menerima (Receiving)'],
            ['kode'=>'A2','domain'=>'Afektif','level'=>2,'deskripsi'=>'Merespons (Responding)'],
            ['kode'=>'A3','domain'=>'Afektif','level'=>3,'deskripsi'=>'Menghargai (Valuing)'],
            ['kode'=>'A4','domain'=>'Afektif','level'=>4,'deskripsi'=>'Mengorganisasi (Organization)'],
            ['kode'=>'A5','domain'=>'Afektif','level'=>5,'deskripsi'=>'Karakterisasi (Characterization)'],
            ['kode'=>'A6','domain'=>'Afektif','level'=>6,'deskripsi'=>'Internalisasi Nilai'],
            ['kode'=>'P1','domain'=>'Psikomotor','level'=>1,'deskripsi'=>'Menirukan (Imitation)'],
            ['kode'=>'P2','domain'=>'Psikomotor','level'=>2,'deskripsi'=>'Manipulasi (Manipulation)'],
            ['kode'=>'P3','domain'=>'Psikomotor','level'=>3,'deskripsi'=>'Presisi (Precision)'],
            ['kode'=>'P4','domain'=>'Psikomotor','level'=>4,'deskripsi'=>'Artikulasi (Articulation)'],
            ['kode'=>'P5','domain'=>'Psikomotor','level'=>5,'deskripsi'=>'Naturalisasi (Naturalization)'],
            ['kode'=>'P6','domain'=>'Psikomotor','level'=>6,'deskripsi'=>'Kreasi Gerak (Creation)'],
        ];
        foreach ($data as $row) {
            CloTaxonomi::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
