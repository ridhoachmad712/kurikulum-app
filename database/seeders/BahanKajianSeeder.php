<?php

namespace Database\Seeders;

use App\Models\BahanKajian;
use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class BahanKajianSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::where('aktif', true)->first();

        if (! $prodi) {
            $this->command->error('Tidak ada ProgramStudi aktif. Seeder dibatalkan.');
            return;
        }

        // ─── 1. Bahan Kajian data ──────────────────────────────────────
        $bkData = [
            ['kode' => 'BK1',  'urutan' => 1,  'nama' => 'Manajemen dan Fungsi-Fungsi Organisasi',
             'deskripsi' => 'Bahan kajian ini membahas prinsip-prinsip manajemen yang meliputi perencanaan, pengorganisasian, pelaksanaan, dan pengendalian. Mahasiswa akan mempelajari bagaimana organisasi beroperasi dan bagaimana fungsi-fungsi tersebut berinteraksi untuk mencapai tujuan organisasi.'],
            ['kode' => 'BK2',  'urutan' => 2,  'nama' => 'Akuntansi',
             'deskripsi' => 'Dalam kajian ini, mahasiswa akan belajar tentang pencatatan, pengklasifikasian, dan pelaporan transaksi keuangan. Fokusnya adalah pada pemahaman laporan keuangan, analisis kinerja perusahaan, dan pengambilan keputusan berbasis data akuntansi.'],
            ['kode' => 'BK3',  'urutan' => 3,  'nama' => 'Ekonomi',
             'deskripsi' => 'Bahan kajian ini mencakup teori-teori ekonomi dasar, termasuk mikroekonomi dan makroekonomi. Mahasiswa akan mempelajari bagaimana individu dan perusahaan membuat keputusan ekonomi serta bagaimana kebijakan pemerintah mempengaruhi perekonomian secara keseluruhan.'],
            ['kode' => 'BK4',  'urutan' => 4,  'nama' => 'Bisnis',
             'deskripsi' => 'Dalam kajian ini, fokus diberikan pada aspek-aspek dasar bisnis, termasuk strategi pemasaran, manajemen operasional, dan pengembangan produk. Mahasiswa akan mempelajari cara merancang dan mengelola bisnis yang sukses dalam berbagai konteks.'],
            ['kode' => 'BK5',  'urutan' => 5,  'nama' => 'Kewirausahaan',
             'deskripsi' => 'Bahan kajian ini mengajarkan mahasiswa tentang proses menciptakan dan mengelola usaha baru. Fokusnya adalah pada inovasi, pengembangan ide bisnis, dan strategi untuk memulai dan mempertahankan usaha yang berkelanjutan.'],
            ['kode' => 'BK6',  'urutan' => 6,  'nama' => 'Komunikasi',
             'deskripsi' => 'Kajian ini menekankan pentingnya komunikasi efektif dalam organisasi. Mahasiswa akan mempelajari berbagai bentuk komunikasi, baik verbal maupun non-verbal, serta bagaimana cara menyampaikan pesan secara efektif dalam konteks bisnis.'],
            ['kode' => 'BK7',  'urutan' => 7,  'nama' => 'Teknologi dan Informasi',
             'deskripsi' => 'Dalam kajian ini, mahasiswa akan mengeksplorasi peran teknologi informasi dalam manajemen dan bisnis. Fokusnya adalah pada penggunaan alat dan sistem informasi untuk meningkatkan efisiensi, pengambilan keputusan, dan inovasi dalam organisasi.'],
            ['kode' => 'BK8',  'urutan' => 8,  'nama' => 'Metodologi dan Karya Ilmiah',
             'deskripsi' => 'Bahan kajian ini membekali mahasiswa dengan keterampilan dalam penelitian dan penulisan ilmiah. Mahasiswa akan belajar tentang desain penelitian, pengumpulan data, analisis, dan penyusunan laporan penelitian yang sistematis dan terstruktur.'],
            ['kode' => 'BK9',  'urutan' => 9,  'nama' => 'Pengembangan Karakter',
             'deskripsi' => 'Kajian ini menanamkan nilai-nilai integritas, etika, tanggung jawab sosial, dan kepemimpinan humanis. Mahasiswa diarahkan untuk menjadi individu yang berkarakter, memiliki empati, serta mampu bekerja sama dalam tim lintas disiplin.'],
            ['kode' => 'BK10', 'urutan' => 10, 'nama' => 'Ciri Khas Program Studi Manajemen FEB UNM',
             'deskripsi' => 'Sebagai pembeda dari program studi sejenis, ciri khas ini menekankan pada penerapan literasi sains, teknologi, dan seni berwawasan kewirausahaan dalam setiap aspek pembelajaran. Mahasiswa diarahkan untuk berpikir lintas disiplin, kreatif, dan inovatif dengan mengintegrasikan nilai-nilai kearifan lokal Sulawesi Selatan dan semangat kewirausahaan global.'],
        ];

        // Hapus data lama untuk prodi ini (idempotent)
        BahanKajian::where('program_studi_id', $prodi->id)->delete();

        $bkModels = [];
        foreach ($bkData as $item) {
            $bk = BahanKajian::create([
                'program_studi_id' => $prodi->id,
                'kode'             => $item['kode'],
                'nama'             => $item['nama'],
                'deskripsi'        => $item['deskripsi'],
                'urutan'           => $item['urutan'],
            ]);
            $bkModels[] = $bk;
        }

        $this->command->info("BahanKajian: " . count($bkModels) . " BK dibuat.");

        // ─── 2. Seed cpl_bahan_kajian (Matrix CPL × BK) ───────────────
        // Rows = CPL1..CPL10, Cols = BK1..BK10
        $cplMatrix = [
            // BK1 BK2 BK3 BK4 BK5 BK6 BK7 BK8 BK9 BK10
            [1,   0,   1,   0,   0,   0,   1,   0,   0,   1],  // CPL1
            [0,   0,   0,   1,   1,   0,   0,   0,   1,   1],  // CPL2
            [0,   0,   0,   0,   0,   1,   0,   0,   1,   1],  // CPL3
            [1,   1,   1,   1,   0,   0,   0,   0,   0,   0],  // CPL4
            [0,   0,   0,   1,   0,   1,   0,   0,   1,   0],  // CPL5
            [1,   1,   0,   0,   0,   0,   1,   0,   0,   0],  // CPL6
            [1,   0,   0,   0,   0,   1,   0,   0,   1,   0],  // CPL7
            [1,   1,   1,   0,   0,   0,   1,   0,   0,   0],  // CPL8
            [0,   1,   0,   1,   1,   0,   0,   0,   0,   0],  // CPL9
            [1,   1,   0,   0,   0,   0,   0,   1,   0,   0],  // CPL10
        ];

        $cpls = Cpl::where('program_studi_id', $prodi->id)->orderBy('urutan')->get();

        if ($cpls->count() < 10) {
            $this->command->warn("CPL di DB hanya {$cpls->count()}, diharapkan 10. Matrix mungkin tidak lengkap.");
        }

        foreach ($cpls as $idx => $cpl) {
            if (! isset($cplMatrix[$idx])) {
                break;
            }
            $row = $cplMatrix[$idx];

            foreach ($bkModels as $bkIdx => $bk) {
                $hasRelation = isset($row[$bkIdx]) && $row[$bkIdx] === 1;

                \DB::table('cpl_bahan_kajian')->updateOrInsert(
                    ['cpl_id' => $cpl->id, 'bahan_kajian_id' => $bk->id],
                    ['has_relation' => $hasRelation, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        $cplBkCount = \DB::table('cpl_bahan_kajian')
            ->where('has_relation', true)
            ->whereIn('bahan_kajian_id', collect($bkModels)->pluck('id'))
            ->count();
        $this->command->info("cpl_bahan_kajian: {$cplBkCount} relasi aktif.");

        // ─── 3. Seed mk_bahan_kajian (Matrix MK × BK) ─────────────────
        // Setiap entry: [nama_mk, [BK1..BK10]]
        $mkMatrix = [
            ['Pendidikan Agama Islam',                    [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Kristen',                  [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Katolik',                  [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Hindu',                    [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Budha',                    [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Konghucu',                 [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Agama Lainnya',                  [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Pancasila',                      [0,0,0,0,0,0,0,0,1,0]],
            ['Pendidikan Kewarganegaraan',                [0,0,0,0,0,0,0,0,1,0]],
            ['Bahasa Indonesia',                          [0,0,0,0,0,1,0,0,1,0]],
            ['Literasi Sains, Teknologi dan Seni',        [0,0,0,0,0,0,1,0,0,0]],
            ['Kewirausahaan',                             [0,0,0,0,1,0,0,0,0,0]],
            ['Bahasa Inggris',                            [0,0,0,0,0,1,0,0,1,0]],
            ['Pengantar Ilmu Ekonomi',                    [0,0,1,1,0,0,0,0,0,0]],
            ['Pengantar Manajemen',                       [1,0,0,1,0,0,0,0,0,0]],
            ['Pengantar Akuntansi',                       [0,1,0,1,0,0,0,0,0,0]],
            ['Pengantar Bisnis',                          [0,0,0,1,0,0,0,0,0,0]],
            ['Bahasa Inggris Bisnis',                     [0,0,0,0,0,1,0,0,0,0]],
            ['Statistik Deskriptif',                      [0,0,0,0,0,0,0,1,0,0]],
            ['Manajemen Keuangan',                        [1,0,0,0,0,0,0,0,0,0]],
            ['Manajemen Pemasaran',                       [1,0,0,0,0,0,0,0,0,0]],
            ['Manajemen Sumber Daya Manusia',             [1,0,0,0,0,0,0,0,0,0]],
            ['Teori Ekonomi Mikro',                       [0,0,1,0,0,0,0,0,0,0]],
            ['Teori Ekonomi Makro',                       [0,0,1,0,0,0,0,0,0,0]],
            ['Matematika Ekonomi',                        [0,0,1,0,0,0,0,0,0,0]],
            ['Filsafat Ilmu Manajemen',                   [1,0,0,0,0,0,0,0,0,0]],
            ['Pengantar Metodologi Penelitian',           [0,0,0,0,0,0,0,1,0,0]],
            ['Etika dan Komunikasi Bisnis',               [0,0,0,0,0,1,0,0,0,0]],
            ['Manajemen Koperasi',                        [1,0,0,0,0,0,0,0,0,0]],
            ['Manajemen Perbankan',                       [1,0,0,0,0,0,0,0,0,0]],
            ['Statistik Inferensial',                     [0,0,0,0,0,0,0,1,0,0]],
            ['Manajemen Operasional',                     [1,0,0,0,0,0,0,0,0,0]],
            ['Manajemen Strategik',                       [1,0,0,0,0,0,0,0,0,0]],
            ['Manajemen Risiko',                          [1,0,0,0,0,0,0,0,0,0]],
            ['Perilaku Keorganisasian',                   [1,0,0,0,0,0,0,0,0,0]],
            ['Sistem Informasi Manajemen',                [0,0,0,0,0,0,1,0,0,0]],
            ['Teori Pengambilan Keputusan',               [1,0,0,0,0,0,0,0,0,0]],
            ['Akuntansi Biaya',                           [0,1,0,0,0,0,0,0,0,0]],
            ['Ekonomi Internasional',                     [0,0,1,0,0,0,0,0,0,0]],
            ['Akuntansi Manajemen',                       [0,1,0,0,0,0,0,0,0,0]],
            ['Ekonometrika',                              [0,0,1,0,0,0,0,1,0,0]],
            ['Penelitian Bisnis',                         [0,0,0,1,0,0,0,0,0,0]],
            ['Perpajakan',                                [0,0,1,0,0,0,0,0,0,0]],
            ['Ekonomi Manajerial',                        [0,0,1,0,0,0,0,0,0,0]],
            ['Kebanksentralan',                           [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Perbankan Syariah',               [0,0,0,0,0,0,0,0,0,1]],
            ['Analisis Laporan Keuangan',                 [0,1,0,0,0,0,0,0,0,1]],
            ['Anggaran Perusahaan',                       [0,1,0,0,0,0,0,0,0,1]],
            ['Lembaga Keuangan Non Bank',                 [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Keuangan Koperasi & UMKM',        [0,0,0,0,0,0,0,0,0,1]],
            ['Studi Kelayakan Keuangan Bisnis',           [0,0,0,1,0,0,0,0,0,1]],
            ['Manajemen Keuangan Strategik',              [0,0,0,0,0,0,0,0,0,1]],
            ['Perilaku Keuangan',                         [0,0,0,0,0,0,0,0,0,1]],
            ['Financial Technology',                      [0,0,0,0,0,0,1,0,0,1]],
            ['Manajemen dan Kebijakan Keuangan Daerah',   [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Investasi dan Portofolio',        [0,0,0,0,0,0,0,0,0,1]],
            ['Keuangan Internasional',                    [0,0,1,0,0,0,0,0,0,1]],
            ['Seminar Manajemen Keuangan',                [0,0,0,0,0,0,0,0,0,1]],
            ['Pemasaran Digital',                         [0,0,0,1,0,0,1,0,0,1]],
            ['Perilaku Konsumen',                         [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Produk dan Merek',                [0,0,0,0,0,0,0,0,0,1]],
            ['Pemasaran Internasional',                   [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Agribisnis',                      [0,0,0,0,0,0,0,0,0,1]],
            ['Komunikasi Pemasaran',                      [0,0,0,0,0,0,0,0,0,1]],
            ['Pemasaran Jasa',                            [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Konten',                          [0,0,0,0,0,0,1,0,0,1]],
            ['Manajemen Penjualan',                       [0,0,0,1,0,0,1,0,0,1]],
            ['Riset Analisis Pasar',                      [0,0,0,0,0,0,0,0,0,1]],
            ['MICE Management',                           [0,0,0,0,0,0,0,0,0,1]],
            ['Strategi Pemasaran',                        [0,0,0,0,0,0,1,0,0,1]],
            ['Manajemen Distribusi',                      [0,0,0,0,0,0,0,0,0,1]],
            ['Seminar Manajemen Pemasaran',               [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen SDM Strategik',                   [0,0,0,0,0,0,0,0,0,1]],
            ['Kepemimpinan',                              [0,0,0,0,0,0,0,0,0,1]],
            ['Sistem Informasi SDM',                      [0,0,0,0,0,0,1,0,0,1]],
            ['Manajemen Konflik dan Hubungan Industrial', [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Kinerja',                         [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Perubahan',                       [0,0,0,0,0,0,0,0,0,1]],
            ['Pelatihan dan Pengembangan SDM',            [0,0,0,0,0,0,0,0,0,1]],
            ['Kesehatan dan Keselamatan Kerja',           [0,0,0,0,0,0,0,0,0,1]],
            ['Organizational Development',                [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Kompensasi',                      [0,0,0,0,0,0,0,0,0,1]],
            ['Psikologi Industri',                        [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen Talenta',                         [0,0,0,0,0,0,0,0,0,1]],
            ['Manajemen SDM Internasional',               [0,0,0,0,0,0,0,0,0,1]],
            ['Seminar Manajemen SDM',                     [0,0,0,0,0,0,0,0,0,1]],
            ['KKP',                                       [0,0,0,0,0,0,0,0,1,0]],
            ['Tugas Akhir',                               [0,0,0,0,0,0,0,1,0,0]],
        ];

        $notFound = [];
        $mkBkRelCount = 0;
        $bkIds = collect($bkModels)->pluck('id')->toArray();

        foreach ($mkMatrix as [$mkNama, $bkRow]) {
            // Cari MK: exact match dulu, lalu LIKE
            $mk = MataKuliah::where('program_studi_id', $prodi->id)
                ->where('nama', $mkNama)
                ->first();

            if (! $mk) {
                // coba LIKE
                $mk = MataKuliah::where('program_studi_id', $prodi->id)
                    ->where('nama', 'LIKE', '%' . $mkNama . '%')
                    ->first();
            }

            if (! $mk) {
                $notFound[] = $mkNama;
                continue;
            }

            foreach ($bkModels as $bkIdx => $bk) {
                $hasRelation = isset($bkRow[$bkIdx]) && $bkRow[$bkIdx] === 1;

                \DB::table('mk_bahan_kajian')->updateOrInsert(
                    ['mata_kuliah_id' => $mk->id, 'bahan_kajian_id' => $bk->id],
                    ['has_relation' => $hasRelation, 'created_at' => now(), 'updated_at' => now()]
                );

                if ($hasRelation) {
                    $mkBkRelCount++;
                }
            }
        }

        $this->command->info("mk_bahan_kajian: {$mkBkRelCount} relasi aktif.");

        if (! empty($notFound)) {
            $this->command->warn("MK tidak ditemukan di DB (" . count($notFound) . " MK):");
            foreach ($notFound as $nama) {
                $this->command->warn("  - {$nama}");
            }
        } else {
            $this->command->info("Semua MK berhasil ditemukan.");
        }

        $this->command->info("=== BahanKajianSeeder selesai ===");
        $this->command->info("Total BK: " . BahanKajian::where('program_studi_id', $prodi->id)->count());
        $this->command->info("Total relasi CPL-BK aktif: " . \DB::table('cpl_bahan_kajian')->where('has_relation', true)->whereIn('bahan_kajian_id', $bkIds)->count());
        $this->command->info("Total relasi MK-BK aktif: " . \DB::table('mk_bahan_kajian')->where('has_relation', true)->whereIn('bahan_kajian_id', $bkIds)->count());
    }
}
