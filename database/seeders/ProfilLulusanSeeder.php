<?php
namespace Database\Seeders;

use App\Models\ProfilLulusan;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProfilLulusanSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::where('kode', 'MNJ-S1')->first();
        if (!$prodi) return;

        $profiles = [
            [
                'kode'    => 'PL1',
                'urutan'  => 1,
                'nama'    => 'Wirausahawan Pemula',
                'deskripsi' => 'Lulusan yang memiliki jiwa wirausahawan yang tangguh, inovatif, adaptif, mampu mengidentifikasi peluang bisnis, serta dapat mendirikan dan mengembangkan usaha secara mandiri berdasarkan prinsip manajemen yang baik.',
            ],
            [
                'kode'    => 'PL2',
                'urutan'  => 2,
                'nama'    => 'Analis Manajemen',
                'deskripsi' => 'Lulusan yang memiliki kemampuan berpikir kritis dan analitis berbasis data dalam mengidentifikasi permasalahan manajerial, merancang solusi, serta mendukung pengambilan keputusan strategis di organisasi bisnis maupun publik.',
            ],
            [
                'kode'    => 'PL3',
                'urutan'  => 3,
                'nama'    => 'Profesional Bidang Manajemen Keuangan',
                'deskripsi' => 'Lulusan yang kompeten dalam pengelolaan dan pengambilan keputusan keuangan, analisis investasi, manajemen risiko keuangan, serta perencanaan keuangan di berbagai jenis organisasi.',
            ],
            [
                'kode'    => 'PL4',
                'urutan'  => 4,
                'nama'    => 'Profesional Bidang Manajemen Pemasaran',
                'deskripsi' => 'Lulusan yang kompeten dalam mengelola kegiatan pemasaran, mengembangkan strategi pemasaran digital dan konvensional, serta mampu menganalisis perilaku konsumen dan tren pasar untuk mencapai tujuan organisasi.',
            ],
            [
                'kode'    => 'PL5',
                'urutan'  => 5,
                'nama'    => 'Profesional Bidang Manajemen SDM',
                'deskripsi' => 'Lulusan yang kompeten dalam pengelolaan sumber daya manusia, meliputi rekrutmen, pengembangan, evaluasi kinerja, kompensasi, serta pengembangan budaya organisasi yang kondusif dan beretika.',
            ],
        ];

        foreach ($profiles as $pl) {
            ProfilLulusan::updateOrCreate(
                ['program_studi_id' => $prodi->id, 'kode' => $pl['kode']],
                array_merge($pl, ['program_studi_id' => $prodi->id])
            );
        }
    }
}
