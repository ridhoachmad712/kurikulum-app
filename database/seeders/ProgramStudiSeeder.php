<?php
namespace Database\Seeders;

use App\Models\ProgramStudi;
use App\Models\Cpl;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::updateOrCreate(
            ['kode' => 'MNJ-S1'],
            [
                'nama'        => 'Manajemen',
                'jenjang'     => 'S1',
                'fakultas'    => 'Fakultas Ekonomi dan Bisnis',
                'universitas' => 'Universitas Negeri Makassar',
                'aktif'       => true,
            ]
        );

        $cpls = [
            ['kode'=>'CPL1','urutan'=>1,'keterangan'=>'CPL UNM','deskripsi'=>'Lulusan memiliki kecakapan literasi sains, teknologi, dan seni dalam upaya menumbuhkan inovasi nasional.'],
            ['kode'=>'CPL2','urutan'=>2,'keterangan'=>'CPL UNM','deskripsi'=>'Lulusan memiliki mindset wirausaha yang tercermin dalam kemampuan berpikir kreatif dan inovatif, memiliki jiwa kepemimpinan, dan mampu menciptakan peluang usaha.'],
            ['kode'=>'CPL3','urutan'=>3,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu menunjukkan karakter yang baik dan Humanis.'],
            ['kode'=>'CPL4','urutan'=>4,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu mengaplikasikan teori manajemen secara efektif.'],
            ['kode'=>'CPL5','urutan'=>5,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu berkomunikasi secara efektif.'],
            ['kode'=>'CPL6','urutan'=>6,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu menggunakan teknologi untuk mendukung pengambilan keputusan manajemen secara tepat.'],
            ['kode'=>'CPL7','urutan'=>7,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu bekerja dengan baik untuk pengembangan diri.'],
            ['kode'=>'CPL8','urutan'=>8,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu memecahkan masalah manajemen secara tepat.'],
            ['kode'=>'CPL9','urutan'=>9,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu membuat rencana bisnis dengan baik.'],
            ['kode'=>'CPL10','urutan'=>10,'keterangan'=>'CPL APSMBI','deskripsi'=>'Lulusan mampu melakukan penelitian manajemen dengan baik dan beretika.'],
        ];

        foreach ($cpls as $cpl) {
            Cpl::updateOrCreate(
                ['program_studi_id' => $prodi->id, 'kode' => $cpl['kode']],
                array_merge($cpl, ['program_studi_id' => $prodi->id])
            );
        }

        $mataKuliahs = [
            ['kode_rps'=>'RPS001','nama'=>'Pendidikan Agama Islam','sks'=>2,'semester'=>1,'urutan'=>1],
            ['kode_rps'=>'RPS002','nama'=>'Pendidikan Agama Kristen','sks'=>2,'semester'=>1,'urutan'=>2],
            ['kode_rps'=>'RPS003','nama'=>'Pendidikan Agama Katolik','sks'=>2,'semester'=>1,'urutan'=>3],
            ['kode_rps'=>'RPS004','nama'=>'Pendidikan Agama Hindu','sks'=>2,'semester'=>1,'urutan'=>4],
            ['kode_rps'=>'RPS005','nama'=>'Pendidikan Agama Budha','sks'=>2,'semester'=>1,'urutan'=>5],
            ['kode_rps'=>'RPS006','nama'=>'Pendidikan Agama Konghucu','sks'=>2,'semester'=>1,'urutan'=>6],
            ['kode_rps'=>'RPS007','nama'=>'Pendidikan Agama Lainnya','sks'=>2,'semester'=>1,'urutan'=>7],
            ['kode_rps'=>'RPS008','nama'=>'Pendidikan Pancasila','sks'=>2,'semester'=>1,'urutan'=>8],
            ['kode_rps'=>'RPS009','nama'=>'Pendidikan Kewarganegaraan','sks'=>2,'semester'=>1,'urutan'=>9],
            ['kode_rps'=>'RPS010','nama'=>'Bahasa Indonesia','sks'=>2,'semester'=>1,'urutan'=>10],
            ['kode_rps'=>'RPS011','nama'=>'Literasi Sains, Teknologi dan Seni','sks'=>2,'semester'=>1,'urutan'=>11],
            ['kode_rps'=>'RPS012','nama'=>'Kewirausahaan','sks'=>3,'semester'=>2,'urutan'=>12],
            ['kode_rps'=>'RPS013','nama'=>'Bahasa Inggris','sks'=>2,'semester'=>1,'urutan'=>13],
            ['kode_rps'=>'RPS014','nama'=>'Pengantar Ilmu Ekonomi','sks'=>3,'semester'=>1,'urutan'=>14],
            ['kode_rps'=>'RPS015','nama'=>'Pengantar Manajemen','sks'=>3,'semester'=>1,'urutan'=>15],
            ['kode_rps'=>'RPS016','nama'=>'Pengantar Akuntansi','sks'=>3,'semester'=>1,'urutan'=>16],
            ['kode_rps'=>'RPS017','nama'=>'Pengantar Bisnis','sks'=>3,'semester'=>1,'urutan'=>17],
            ['kode_rps'=>'RPS018','nama'=>'Bahasa Inggris Bisnis','sks'=>2,'semester'=>2,'urutan'=>18],
            ['kode_rps'=>'RPS019','nama'=>'Statistik Deskriptif','sks'=>3,'semester'=>2,'urutan'=>19],
            ['kode_rps'=>'RPS020','nama'=>'Manajemen Keuangan','sks'=>3,'semester'=>3,'urutan'=>20],
            ['kode_rps'=>'RPS021','nama'=>'Manajemen Pemasaran','sks'=>3,'semester'=>3,'urutan'=>21],
            ['kode_rps'=>'RPS022','nama'=>'Manajemen Sumber Daya Manusia','sks'=>3,'semester'=>3,'urutan'=>22],
            ['kode_rps'=>'RPS023','nama'=>'Teori Ekonomi Mikro','sks'=>3,'semester'=>2,'urutan'=>23],
            ['kode_rps'=>'RPS024','nama'=>'Teori Ekonomi Makro','sks'=>3,'semester'=>2,'urutan'=>24],
            ['kode_rps'=>'RPS025','nama'=>'Matematika Ekonomi','sks'=>3,'semester'=>2,'urutan'=>25],
            ['kode_rps'=>'RPS026','nama'=>'Filsafat Ilmu Manajemen','sks'=>2,'semester'=>3,'urutan'=>26],
            ['kode_rps'=>'RPS027','nama'=>'Pengantar Metodologi Penelitian','sks'=>3,'semester'=>3,'urutan'=>27],
            ['kode_rps'=>'RPS028','nama'=>'Etika dan Komunikasi Bisnis','sks'=>3,'semester'=>3,'urutan'=>28],
            ['kode_rps'=>'RPS029','nama'=>'Manajemen Koperasi','sks'=>3,'semester'=>4,'urutan'=>29],
            ['kode_rps'=>'RPS030','nama'=>'Manajemen Perbankan','sks'=>3,'semester'=>4,'urutan'=>30],
            ['kode_rps'=>'RPS031','nama'=>'Statistik Inferensial','sks'=>3,'semester'=>3,'urutan'=>31],
            ['kode_rps'=>'RPS032','nama'=>'Manajemen Operasional','sks'=>3,'semester'=>4,'urutan'=>32],
            ['kode_rps'=>'RPS033','nama'=>'Manajemen Strategik','sks'=>3,'semester'=>5,'urutan'=>33],
            ['kode_rps'=>'RPS034','nama'=>'Manajemen Risiko','sks'=>3,'semester'=>5,'urutan'=>34],
            ['kode_rps'=>'RPS035','nama'=>'Perilaku Keorganisasian','sks'=>3,'semester'=>4,'urutan'=>35],
            ['kode_rps'=>'RPS036','nama'=>'Sistem Informasi Manajemen','sks'=>3,'semester'=>4,'urutan'=>36],
            ['kode_rps'=>'RPS037','nama'=>'Teori Pengambilan Keputusan','sks'=>3,'semester'=>5,'urutan'=>37],
            ['kode_rps'=>'RPS038','nama'=>'Akuntansi Biaya','sks'=>3,'semester'=>4,'urutan'=>38],
            ['kode_rps'=>'RPS039','nama'=>'Ekonomi Internasional','sks'=>3,'semester'=>4,'urutan'=>39],
            ['kode_rps'=>'RPS040','nama'=>'Akuntansi Manajemen','sks'=>3,'semester'=>5,'urutan'=>40],
            ['kode_rps'=>'RPS041','nama'=>'Ekonometrika','sks'=>3,'semester'=>5,'urutan'=>41],
            ['kode_rps'=>'RPS042','nama'=>'Penelitian Bisnis','sks'=>3,'semester'=>6,'urutan'=>42],
            ['kode_rps'=>'RPS043','nama'=>'Perpajakan','sks'=>3,'semester'=>5,'urutan'=>43],
            ['kode_rps'=>'RPS044','nama'=>'Ekonomi Manajerial','sks'=>3,'semester'=>5,'urutan'=>44],
            ['kode_rps'=>'RPS045','nama'=>'Kebanksentralan','sks'=>3,'semester'=>5,'urutan'=>45],
            ['kode_rps'=>'RPS046','nama'=>'Manajemen Perbankan Syariah','sks'=>3,'semester'=>6,'urutan'=>46],
            ['kode_rps'=>'RPS047','nama'=>'Analisis Laporan Keuangan','sks'=>3,'semester'=>6,'urutan'=>47],
            ['kode_rps'=>'RPS048','nama'=>'Anggaran Perusahaan','sks'=>3,'semester'=>6,'urutan'=>48],
            ['kode_rps'=>'RPS049','nama'=>'Lembaga Keuangan Non Bank','sks'=>3,'semester'=>6,'urutan'=>49],
            ['kode_rps'=>'RPS050','nama'=>'Manajemen Keuangan Koperasi & UMKM','sks'=>3,'semester'=>6,'urutan'=>50],
            ['kode_rps'=>'RPS051','nama'=>'Studi Kelayakan Keuangan Bisnis','sks'=>3,'semester'=>7,'urutan'=>51],
            ['kode_rps'=>'RPS052','nama'=>'Manajemen Keuangan Strategik','sks'=>3,'semester'=>7,'urutan'=>52],
            ['kode_rps'=>'RPS053','nama'=>'Perilaku Keuangan','sks'=>3,'semester'=>7,'urutan'=>53],
            ['kode_rps'=>'RPS054','nama'=>'Financial Technology','sks'=>3,'semester'=>7,'urutan'=>54],
            ['kode_rps'=>'RPS055','nama'=>'Manajemen dan Kebijakan Keuangan Daerah','sks'=>3,'semester'=>7,'urutan'=>55],
            ['kode_rps'=>'RPS056','nama'=>'Manajemen Investasi dan Portofolio','sks'=>3,'semester'=>7,'urutan'=>56],
            ['kode_rps'=>'RPS057','nama'=>'Keuangan Internasional','sks'=>3,'semester'=>7,'urutan'=>57],
            ['kode_rps'=>'RPS058','nama'=>'Seminar Manajemen Keuangan','sks'=>3,'semester'=>8,'urutan'=>58],
            ['kode_rps'=>'RPS059','nama'=>'Pemasaran Digital','sks'=>3,'semester'=>5,'urutan'=>59],
            ['kode_rps'=>'RPS060','nama'=>'Perilaku Konsumen','sks'=>3,'semester'=>5,'urutan'=>60],
            ['kode_rps'=>'RPS061','nama'=>'Manajemen Produk dan Merek','sks'=>3,'semester'=>6,'urutan'=>61],
            ['kode_rps'=>'RPS062','nama'=>'Pemasaran Internasional','sks'=>3,'semester'=>6,'urutan'=>62],
            ['kode_rps'=>'RPS063','nama'=>'Manajemen Agribisnis','sks'=>3,'semester'=>6,'urutan'=>63],
            ['kode_rps'=>'RPS064','nama'=>'Komunikasi Pemasaran','sks'=>3,'semester'=>6,'urutan'=>64],
            ['kode_rps'=>'RPS065','nama'=>'Pemasaran Jasa','sks'=>3,'semester'=>6,'urutan'=>65],
            ['kode_rps'=>'RPS066','nama'=>'Manajemen Konten','sks'=>3,'semester'=>7,'urutan'=>66],
            ['kode_rps'=>'RPS067','nama'=>'Manajemen Penjualan','sks'=>3,'semester'=>7,'urutan'=>67],
            ['kode_rps'=>'RPS068','nama'=>'Riset Analisis Pasar','sks'=>3,'semester'=>7,'urutan'=>68],
            ['kode_rps'=>'RPS069','nama'=>'MICE Management','sks'=>3,'semester'=>7,'urutan'=>69],
            ['kode_rps'=>'RPS070','nama'=>'Strategi Pemasaran','sks'=>3,'semester'=>7,'urutan'=>70],
            ['kode_rps'=>'RPS071','nama'=>'Manajemen Distribusi','sks'=>3,'semester'=>7,'urutan'=>71],
            ['kode_rps'=>'RPS072','nama'=>'Seminar Manajemen Pemasaran','sks'=>3,'semester'=>8,'urutan'=>72],
            ['kode_rps'=>'RPS073','nama'=>'Manajemen SDM Strategik','sks'=>3,'semester'=>5,'urutan'=>73],
            ['kode_rps'=>'RPS074','nama'=>'Kepemimpinan','sks'=>3,'semester'=>5,'urutan'=>74],
            ['kode_rps'=>'RPS075','nama'=>'Sistem Informasi SDM','sks'=>3,'semester'=>6,'urutan'=>75],
            ['kode_rps'=>'RPS076','nama'=>'Manajemen Konflik dan Hubungan Industrial','sks'=>3,'semester'=>6,'urutan'=>76],
            ['kode_rps'=>'RPS077','nama'=>'Manajemen Kinerja','sks'=>3,'semester'=>6,'urutan'=>77],
            ['kode_rps'=>'RPS078','nama'=>'Manajemen Perubahan','sks'=>3,'semester'=>6,'urutan'=>78],
            ['kode_rps'=>'RPS079','nama'=>'Pelatihan dan Pengembangan SDM','sks'=>3,'semester'=>7,'urutan'=>79],
            ['kode_rps'=>'RPS080','nama'=>'Kesehatan dan Keselamatan Kerja','sks'=>3,'semester'=>7,'urutan'=>80],
            ['kode_rps'=>'RPS081','nama'=>'Organizational Development','sks'=>3,'semester'=>7,'urutan'=>81],
            ['kode_rps'=>'RPS082','nama'=>'Manajemen Kompensasi','sks'=>3,'semester'=>7,'urutan'=>82],
            ['kode_rps'=>'RPS083','nama'=>'Psikologi Industri','sks'=>3,'semester'=>7,'urutan'=>83],
            ['kode_rps'=>'RPS084','nama'=>'Manajemen Talenta','sks'=>3,'semester'=>7,'urutan'=>84],
            ['kode_rps'=>'RPS085','nama'=>'Manajemen SDM Internasional','sks'=>3,'semester'=>7,'urutan'=>85],
            ['kode_rps'=>'RPS086','nama'=>'Seminar Manajemen SDM','sks'=>3,'semester'=>8,'urutan'=>86],
            ['kode_rps'=>'RPS087','nama'=>'KKP','sks'=>3,'semester'=>7,'urutan'=>87],
            ['kode_rps'=>'RPS088','nama'=>'Tugas Akhir','sks'=>6,'semester'=>8,'urutan'=>88],
        ];

        foreach ($mataKuliahs as $mk) {
            MataKuliah::updateOrCreate(
                ['kode_rps' => $mk['kode_rps']],
                array_merge($mk, ['program_studi_id' => $prodi->id, 'aktif' => true])
            );
        }
    }
}
