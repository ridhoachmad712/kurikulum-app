<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $upd = fn(int $id, array $data) =>
            DB::table('mata_kuliahs')->where('id', $id)->update(array_merge($data, ['updated_at' => $now]));

        // ── 1. Hapus 6 MK Agama ekstra (simpan Pendidikan Agama Islam → rename) ──
        DB::table('mata_kuliahs')->whereIn('id', [2, 3, 4, 5, 6, 7])->delete();

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 1  (9 MK)
        // ══════════════════════════════════════════════════════════════
        $upd(1,  ['kode_rps'=>'x00CWK02001','nama'=>'Pendidikan Agama',              'sks'=>2,'semester'=>1,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(8,  ['kode_rps'=>'x00CWK02008','nama'=>'Pendidikan Pancasila',           'sks'=>2,'semester'=>1,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(10, ['kode_rps'=>'x00CWK02010','nama'=>'Bahasa Indonesia',               'sks'=>2,'semester'=>1,'urutan'=>3,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(11, ['kode_rps'=>'x00CPU02011','nama'=>'Literasi Sains, Teknologi dan Seni','sks'=>3,'semester'=>1,'urutan'=>4,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(13, ['kode_rps'=>'I03CKP02111','nama'=>'Bahasa Inggris',                 'sks'=>2,'semester'=>1,'urutan'=>5,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(14, ['kode_rps'=>'I03CKP02112','nama'=>'Pengantar Ilmu Ekonomi',          'sks'=>2,'semester'=>1,'urutan'=>6,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(15, ['kode_rps'=>'I03CKP03113','nama'=>'Pengantar Manajemen',             'sks'=>3,'semester'=>1,'urutan'=>7,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(16, ['kode_rps'=>'I03CKP02114','nama'=>'Pengantar Akuntansi',             'sks'=>2,'semester'=>1,'urutan'=>8,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(17, ['kode_rps'=>'I03CKP02115','nama'=>'Pengantar Bisnis',                'sks'=>2,'semester'=>1,'urutan'=>9,'konsentrasi'=>null,'is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 2  (7 MK)
        // ══════════════════════════════════════════════════════════════
        $upd(9,  ['kode_rps'=>'x00CWK02009','nama'=>'Pendidikan Kewarganegaraan',     'sks'=>2,'semester'=>2,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(12, ['kode_rps'=>'x00CPU02012','nama'=>'Kewirausahaan',                   'sks'=>3,'semester'=>2,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(18, ['kode_rps'=>'I03CKP03203','nama'=>'Bahasa Inggris Bisnis',           'sks'=>3,'semester'=>2,'urutan'=>3,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(19, ['kode_rps'=>'I03CKP03204','nama'=>'Statistik Deskriptif',            'sks'=>3,'semester'=>2,'urutan'=>4,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(20, ['kode_rps'=>'I03CKP03205','nama'=>'Manajemen Keuangan',              'sks'=>3,'semester'=>2,'urutan'=>5,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(21, ['kode_rps'=>'I03CKP03206','nama'=>'Manajemen Pemasaran',             'sks'=>3,'semester'=>2,'urutan'=>6,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(22, ['kode_rps'=>'I03CKP03207','nama'=>'Manajemen Sumber Daya Manusia',  'sks'=>3,'semester'=>2,'urutan'=>7,'konsentrasi'=>null,'is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 3  (8 MK)
        // ══════════════════════════════════════════════════════════════
        $upd(23, ['kode_rps'=>'I03CKP03301','nama'=>'Teori Ekonomi Mikro',             'sks'=>3,'semester'=>3,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(24, ['kode_rps'=>'I03CKP03302','nama'=>'Teori Ekonomi Makro',             'sks'=>3,'semester'=>3,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(25, ['kode_rps'=>'I03CKP03303','nama'=>'Matematika Ekonomi',              'sks'=>3,'semester'=>3,'urutan'=>3,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(26, ['kode_rps'=>'I03CKP02304','nama'=>'Filsafat Ilmu Manajemen',         'sks'=>2,'semester'=>3,'urutan'=>4,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(27, ['kode_rps'=>'I03CKP03305','nama'=>'Pengantar Metodologi Penelitian', 'sks'=>3,'semester'=>3,'urutan'=>5,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(28, ['kode_rps'=>'I03CKP03306','nama'=>'Etika dan Komunikasi Bisnis',     'sks'=>3,'semester'=>3,'urutan'=>6,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(29, ['kode_rps'=>'I03CKP03307','nama'=>'Manajemen Koperasi',              'sks'=>3,'semester'=>3,'urutan'=>7,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(30, ['kode_rps'=>'I03CKP03308','nama'=>'Manajemen Perbankan',             'sks'=>3,'semester'=>3,'urutan'=>8,'konsentrasi'=>null,'is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 4  (8 MK)
        // ══════════════════════════════════════════════════════════════
        $upd(31, ['kode_rps'=>'I03CKP03401','nama'=>'Statistik Inferensial',           'sks'=>3,'semester'=>4,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(32, ['kode_rps'=>'I03CKP03402','nama'=>'Manajemen Operasional',           'sks'=>3,'semester'=>4,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(33, ['kode_rps'=>'I03CKP03403','nama'=>'Manajemen Strategik',             'sks'=>3,'semester'=>4,'urutan'=>3,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(34, ['kode_rps'=>'I03CKP03404','nama'=>'Manajemen Risiko',                'sks'=>3,'semester'=>4,'urutan'=>4,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(35, ['kode_rps'=>'I03CKP03405','nama'=>'Perilaku Keorganisasian',         'sks'=>3,'semester'=>4,'urutan'=>5,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(36, ['kode_rps'=>'I03CKP03406','nama'=>'Sistem Informasi Manajemen',      'sks'=>3,'semester'=>4,'urutan'=>6,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(37, ['kode_rps'=>'I03CKP03407','nama'=>'Teori Pengambilan Keputusan',     'sks'=>3,'semester'=>4,'urutan'=>7,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(38, ['kode_rps'=>'I03CKP03408','nama'=>'Akuntansi Biaya',                 'sks'=>3,'semester'=>4,'urutan'=>8,'konsentrasi'=>null,'is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 5  (12 MK: 6 wajib + 2+2+2 konsentrasi)
        // ══════════════════════════════════════════════════════════════
        $upd(39, ['kode_rps'=>'I03CKP02501','nama'=>'Ekonomi Internasional',           'sks'=>2,'semester'=>5,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(40, ['kode_rps'=>'I03CKP03502','nama'=>'Akuntansi Manajemen',             'sks'=>3,'semester'=>5,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(41, ['kode_rps'=>'I03CKP03503','nama'=>'Ekonometrika',                    'sks'=>3,'semester'=>5,'urutan'=>3,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(42, ['kode_rps'=>'I03CKP03504','nama'=>'Penelitian Bisnis',               'sks'=>3,'semester'=>5,'urutan'=>4,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(43, ['kode_rps'=>'I03CKP03505','nama'=>'Perpajakan',                      'sks'=>3,'semester'=>5,'urutan'=>5,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(44, ['kode_rps'=>'I03CKP03506','nama'=>'Ekonomi Manajerial',              'sks'=>3,'semester'=>5,'urutan'=>6,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(45, ['kode_rps'=>'I03CKP03507','nama'=>'Kebanksentralan',                 'sks'=>3,'semester'=>5,'urutan'=>7,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(46, ['kode_rps'=>'I03CKP03508','nama'=>'Manajemen Perbankan Syariah',     'sks'=>3,'semester'=>5,'urutan'=>8,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(59, ['kode_rps'=>'I03CKP03509','nama'=>'Pemasaran Digital',               'sks'=>3,'semester'=>5,'urutan'=>9,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(60, ['kode_rps'=>'I03CKP03510','nama'=>'Perilaku Konsumen',               'sks'=>3,'semester'=>5,'urutan'=>10,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(84, ['kode_rps'=>'I03CKP03511','nama'=>'Talent Management',               'sks'=>3,'semester'=>5,'urutan'=>11,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        $upd(74, ['kode_rps'=>'I03CKP03512','nama'=>'Kepemimpinan',                    'sks'=>3,'semester'=>5,'urutan'=>12,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 6  (27 MK: 9+9+9 per konsentrasi)
        // ══════════════════════════════════════════════════════════════
        // Manajemen Keuangan — 3 wajib
        $upd(47, ['kode_rps'=>'I03CKP03601','nama'=>'Analisis Laporan Keuangan',               'sks'=>3,'semester'=>6,'urutan'=>1,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(48, ['kode_rps'=>'I03CKP03602','nama'=>'Anggaran Perusahaan',                     'sks'=>3,'semester'=>6,'urutan'=>2,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(49, ['kode_rps'=>'I03CKP03603','nama'=>'Lembaga Keuangan Non Bank',               'sks'=>3,'semester'=>6,'urutan'=>3,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        // Manajemen Keuangan — 6 pilihan
        $upd(50, ['kode_rps'=>'I03CKP03604','nama'=>'Manajemen Keuangan Koperasi & UMKM',     'sks'=>3,'semester'=>6,'urutan'=>4,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);
        $upd(51, ['kode_rps'=>'I03CKP03605','nama'=>'Studi Kelayakan Keuangan Bisnis',         'sks'=>3,'semester'=>6,'urutan'=>5,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);
        $upd(52, ['kode_rps'=>'I03CKP03606','nama'=>'Manajemen Keuangan Strategik',            'sks'=>3,'semester'=>6,'urutan'=>6,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);
        $upd(53, ['kode_rps'=>'I03CKP03607','nama'=>'Perilaku Keuangan',                       'sks'=>3,'semester'=>6,'urutan'=>7,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);
        $upd(54, ['kode_rps'=>'I03CKP03608','nama'=>'Financial Technology',                    'sks'=>3,'semester'=>6,'urutan'=>8,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);
        $upd(55, ['kode_rps'=>'I03CKP03609','nama'=>'Manajemen dan Kebijakan Keuangan Daerah','sks'=>3,'semester'=>6,'urutan'=>9,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>1]);

        // Manajemen Pemasaran — 3 wajib
        $upd(61, ['kode_rps'=>'I03CKP03610','nama'=>'Manajemen Produk dan Merek',              'sks'=>3,'semester'=>6,'urutan'=>10,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(62, ['kode_rps'=>'I03CKP03611','nama'=>'Pemasaran Internasional',                 'sks'=>3,'semester'=>6,'urutan'=>11,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(63, ['kode_rps'=>'I03CKP03612','nama'=>'Manajemen Agribisnis',                    'sks'=>3,'semester'=>6,'urutan'=>12,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        // Manajemen Pemasaran — 6 pilihan
        $upd(64, ['kode_rps'=>'I03CKP03613','nama'=>'Komunikasi Pemasaran',                    'sks'=>3,'semester'=>6,'urutan'=>13,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);
        $upd(65, ['kode_rps'=>'I03CKP03614','nama'=>'Pemasaran Jasa',                          'sks'=>3,'semester'=>6,'urutan'=>14,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);
        $upd(66, ['kode_rps'=>'I03CKP03615','nama'=>'Manajemen Konten',                        'sks'=>3,'semester'=>6,'urutan'=>15,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);
        $upd(67, ['kode_rps'=>'I03CKP03616','nama'=>'Manajemen Penjualan',                     'sks'=>3,'semester'=>6,'urutan'=>16,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);
        $upd(68, ['kode_rps'=>'I03CKP03617','nama'=>'Riset Analisis Pasar',                    'sks'=>3,'semester'=>6,'urutan'=>17,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);
        $upd(69, ['kode_rps'=>'I03CKP03618','nama'=>'MICE Management',                         'sks'=>3,'semester'=>6,'urutan'=>18,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>1]);

        // Manajemen SDM — 3 wajib
        $upd(73, ['kode_rps'=>'I03CKP03619','nama'=>'Manajemen SDM Strategik',                 'sks'=>3,'semester'=>6,'urutan'=>19,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        $upd(75, ['kode_rps'=>'I03CKP03620','nama'=>'Sistem Informasi SDM',                    'sks'=>3,'semester'=>6,'urutan'=>20,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        $upd(76, ['kode_rps'=>'I03CKP03621','nama'=>'Manajemen Konflik dan Hubungan Industrial','sks'=>3,'semester'=>6,'urutan'=>21,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        // Manajemen SDM — 6 pilihan
        $upd(77, ['kode_rps'=>'I03CKP03622','nama'=>'Manajemen Kinerja',                       'sks'=>3,'semester'=>6,'urutan'=>22,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);
        $upd(78, ['kode_rps'=>'I03CKP03623','nama'=>'Manajemen Perubahan',                     'sks'=>3,'semester'=>6,'urutan'=>23,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);
        $upd(79, ['kode_rps'=>'I03CKP03624','nama'=>'Pelatihan dan Pengembangan SDM',          'sks'=>3,'semester'=>6,'urutan'=>24,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);
        $upd(80, ['kode_rps'=>'I03CKP03625','nama'=>'Kesehatan dan Keselamatan Kerja',         'sks'=>3,'semester'=>6,'urutan'=>25,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);
        $upd(81, ['kode_rps'=>'I03CKP03626','nama'=>'Organizational Development',              'sks'=>3,'semester'=>6,'urutan'=>26,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);
        $upd(82, ['kode_rps'=>'I03CKP03627','nama'=>'Manajemen Kompensasi',                    'sks'=>3,'semester'=>6,'urutan'=>27,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>1]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 7  (9 MK: 3+3+3 per konsentrasi)
        // ══════════════════════════════════════════════════════════════
        $upd(56, ['kode_rps'=>'I03CKP03701','nama'=>'Manajemen Investasi dan Portofolio',      'sks'=>3,'semester'=>7,'urutan'=>1,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(57, ['kode_rps'=>'I03CKP03702','nama'=>'Keuangan Internasional',                  'sks'=>3,'semester'=>7,'urutan'=>2,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(58, ['kode_rps'=>'I03CKP03703','nama'=>'Seminar Manajemen Keuangan',              'sks'=>3,'semester'=>7,'urutan'=>3,'konsentrasi'=>'Manajemen Keuangan','is_pilihan'=>0]);
        $upd(70, ['kode_rps'=>'I03CKP03704','nama'=>'Strategi Pemasaran',                      'sks'=>3,'semester'=>7,'urutan'=>4,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(71, ['kode_rps'=>'I03CKP03705','nama'=>'Manajemen Distribusi',                    'sks'=>3,'semester'=>7,'urutan'=>5,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(72, ['kode_rps'=>'I03CKP03706','nama'=>'Seminar Manajemen Pemasaran',             'sks'=>3,'semester'=>7,'urutan'=>6,'konsentrasi'=>'Manajemen Pemasaran','is_pilihan'=>0]);
        $upd(83, ['kode_rps'=>'I03CKP03707','nama'=>'Psikologi Industri',                      'sks'=>3,'semester'=>7,'urutan'=>7,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        $upd(85, ['kode_rps'=>'I03CKP03708','nama'=>'Manajemen SDM Internasional',             'sks'=>3,'semester'=>7,'urutan'=>8,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);
        $upd(86, ['kode_rps'=>'I03CKP03709','nama'=>'Seminar Manajemen SDM',                   'sks'=>3,'semester'=>7,'urutan'=>9,'konsentrasi'=>'Manajemen SDM','is_pilihan'=>0]);

        // ══════════════════════════════════════════════════════════════
        // SEMESTER 8  (2 MK)
        // ══════════════════════════════════════════════════════════════
        $upd(87, ['kode_rps'=>'I03CKP04801','nama'=>'KKP',     'sks'=>4,'semester'=>8,'urutan'=>1,'konsentrasi'=>null,'is_pilihan'=>0]);
        $upd(88, ['kode_rps'=>'I03CKP06802','nama'=>'Skripsi', 'sks'=>6,'semester'=>8,'urutan'=>2,'konsentrasi'=>null,'is_pilihan'=>0]);
    }

    public function down(): void
    {
        // Tidak ada rollback untuk data migration ini
    }
};
