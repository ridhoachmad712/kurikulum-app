<?php
namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenanggungjawabSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::where('kode', 'MNJ-S1')->firstOrFail();
        $psId  = $prodi->id;

        // 1. Buat/update akun dosen
        $dosenMap = []; // name => user_id

        $dosens = [
            ['A. Nurfiana Haz, S.E., M.M.', 'a.nurfiana@kurikulum.id'],
            ['A. Reski Almaida Dg Macenning, S.Pd., M.M.', 'a.reski@kurikulum.id'],
            ['Abdul Muis, S.E., M.M.', 'abdul.muis@kurikulum.id'],
            ['Abdul Rahman', 'abdul.rahman@kurikulum.id'],
            ['Achmad Ridha, S.M., M.M.', 'achmad.ridha@kurikulum.id'],
            ['Ade Vidya Eryanti K., S.M., M.M.', 'ade.vidya@kurikulum.id'],
            ['Anang Setiawan, S.M., M.M.', 'anang.setiawan@kurikulum.id'],
            ['Andi Aryani Hardiyanti, S.Si., M.M.', 'andi.aryani@kurikulum.id'],
            ['Andi Dewi Angreyani', 'andi.dewi@kurikulum.id'],
            ['Chairunnisaa M Z Huzaen, S.E., M.S.M.', 'chairunnisaa.m@kurikulum.id'],
            ['Deddy Ibrahim Rauf, S.E., M.M.', 'deddy.ibrahim@kurikulum.id'],
            ['Dr. Azlan Azhari, S.E., M.M.', 'azlan.azhari@kurikulum.id'],
            ['Dr. Hasnidar, S.E., M.M.', 'hasnidar@kurikulum.id'],
            ['Dr. Nurul Fadilah Aswar, S.E., M.M.', 'nurul.fadilah@kurikulum.id'],
            ['Fakhirah Husain, S.E., M.M.', 'fakhirah.husain@kurikulum.id'],
            ['Fitriani Rahim, S.E., M.M.', 'fitriani.rahim@kurikulum.id'],
            ['Indah Lestari Anwar, S.E., M.S.M.', 'indah.lestari@kurikulum.id'],
            ['Indri Iswardhani, S.E., M.M.', 'indri.iswardhani@kurikulum.id'],
            ['Josafat Gracia Ginting, S.M., M.M.', 'josafat.gracia@kurikulum.id'],
            ['M. Yunasri Ridhoh, S.Pd., M.Pd.', 'm.yunasri@kurikulum.id'],
            ['Mufidatul Azmi, S.M., M.M.', 'mufidatul.azmi@kurikulum.id'],
            ['Muh Al Fatah Arief Putra, S.E., M.M.', 'muh.al@kurikulum.id'],
            ['Muh. Nurfadel Hamzah, S.Tr.Pel., M.M.', 'muh.nurfadel@kurikulum.id'],
            ['Muhaidir Ikram M., S.M., M.M.', 'muhaidir.ikram@kurikulum.id'],
            ['Muhammad Rijal Alim Rahmat', 'muhammad.rijal@kurikulum.id'],
            ['Nidrah, S.E., M.M.', 'nidrah@kurikulum.id'],
            ['Nulthazam Sarah, S.E., M.M.', 'nulthazam.sarah@kurikulum.id'],
            ['Nur Fadilah Ayu Sandira, S.Pd., M.M.', 'nur.fadilah@kurikulum.id'],
            ['Nurhaedah, S.E., M.Si.', 'nurhaedah@kurikulum.id'],
            ['Prof. Dr. Romansyah Sahabuddin, S.E., M.Si.', 'dr.romansyah@kurikulum.id'],
            ['Prof. Dr. Romansyah Sahabuddin,S.E.,M.Si', 'dr.romansyah@kurikulum.id'],
            ['Rahmat Burhamzah, S.Pd., M.M., Ak.', 'rahmat.burhamzah@kurikulum.id'],
            ['Rezky Amalia Hamka, S.E., M.M.', 'rezky.amalia@kurikulum.id'],
            ['Ridfan Abadi, S.E., M.M.', 'ridfan.abadi@kurikulum.id'],
            ['Ridwan Andi Mattoliang, S.Pd., M.Pd.', 'ridwan.andi@kurikulum.id'],
            ['Rika Kurniawati, S.E., M.M.', 'rika.kurniawati@kurikulum.id'],
            ['Rostina Alimuddin, S.Pd., M.Pd.', 'rostina.alimuddin@kurikulum.id'],
            ['Sri Astuti Nasir, S.Pd., M.M.', 'sri.astuti@kurikulum.id'],
            ['Sri Astuti Nasir, S.Pd.,M.M', 'sri.astuti@kurikulum.id'],
            ['Stefanie Inggried', 'stefanie.inggried@kurikulum.id'],
            ['Ushwa Dwi Masrurah Arifin Bando, S.Pd.I., M.Pd.', 'ushwa.dwi@kurikulum.id'],
            ['Widhi Nugraha SD', 'widhi.nugraha@kurikulum.id'],
            ['Wiwin Riski Windarsari, S.E., M.M.', 'wiwin.riski@kurikulum.id'],
        ];

        foreach ($dosens as [$name, $email]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $name,
                    'password'          => Hash::make('password'),
                    'program_studi_id'  => $psId,
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles(['dosen']);
            $dosenMap[$name] = $user->id;
        }

        // 2. Tugaskan ke MK (mk_dosens)
        $assignments = [
            ['Pendidikan Agama Islam', 'Ushwa Dwi Masrurah Arifin Bando, S.Pd.I., M.Pd.'],
            ['Pendidikan Agama Kristen', 'Josafat Gracia Ginting, S.M., M.M.'],
            ['Pendidikan Agama Katolik', 'Stefanie Inggried'],
            ['Pendidikan Pancasila', 'M. Yunasri Ridhoh, S.Pd., M.Pd.'],
            ['Pendidikan Kewarganegaraan', 'M. Yunasri Ridhoh, S.Pd., M.Pd.'],
            ['Bahasa Indonesia', 'Ridwan Andi Mattoliang, S.Pd., M.Pd.'],
            ['Literasi Sains, Teknologi dan Seni', 'Achmad Ridha, S.M., M.M.'],
            ['Kewirausahaan', 'Fitriani Rahim, S.E., M.M.'],
            ['Bahasa Inggris', 'Rostina Alimuddin, S.Pd., M.Pd.'],
            ['Pengantar Ilmu Ekonomi', 'Prof. Dr. Romansyah Sahabuddin, S.E., M.Si.'],
            ['Pengantar Manajemen', 'Ade Vidya Eryanti K., S.M., M.M.'],
            ['Pengantar Akuntansi', 'Nidrah, S.E., M.M.'],
            ['Pengantar Bisnis', 'Dr. Hasnidar, S.E., M.M.'],
            ['Bahasa Inggris Bisnis', 'Rostina Alimuddin, S.Pd., M.Pd.'],
            ['Statistik Deskriptif', 'Prof. Dr. Romansyah Sahabuddin,S.E.,M.Si'],
            ['Manajemen Keuangan', 'Deddy Ibrahim Rauf, S.E., M.M.'],
            ['Manajemen Pemasaran', 'Andi Aryani Hardiyanti, S.Si., M.M.'],
            ['Manajemen Sumber Daya Manusia', 'Ridfan Abadi, S.E., M.M.'],
            ['Teori Ekonomi Mikro', 'Muhaidir Ikram M., S.M., M.M.'],
            ['Teori Ekonomi Makro', 'Abdul Rahman'],
            ['Matematika Ekonomi', 'Fakhirah Husain, S.E., M.M.'],
            ['Filsafat Ilmu Manajemen', 'Dr. Azlan Azhari, S.E., M.M.'],
            ['Pengantar Metodologi Penelitian', 'Muh. Nurfadel Hamzah, S.Tr.Pel., M.M.'],
            ['Etika dan Komunikasi Bisnis', 'Nur Fadilah Ayu Sandira, S.Pd., M.M.'],
            ['Manajemen Koperasi', 'Nurhaedah, S.E., M.Si.'],
            ['Manajemen Perbankan', 'Rika Kurniawati, S.E., M.M.'],
            ['Statistik Inferensial', 'Prof. Dr. Romansyah Sahabuddin,S.E.,M.Si'],
            ['Manajemen Operasional', 'Nur Fadilah Ayu Sandira, S.Pd., M.M.'],
            ['Manajemen Strategik', 'Muhammad Rijal Alim Rahmat'],
            ['Manajemen Risiko', 'Indri Iswardhani, S.E., M.M.'],
            ['Perilaku Keorganisasian', 'Anang Setiawan, S.M., M.M.'],
            ['Sistem Informasi Manajemen', 'Achmad Ridha, S.M., M.M.'],
            ['Teori Pengambilan Keputusan', 'Dr. Azlan Azhari, S.E., M.M.'],
            ['Akuntansi Biaya', 'A. Reski Almaida Dg Macenning, S.Pd., M.M.'],
            ['Ekonomi Internasional', 'Wiwin Riski Windarsari, S.E., M.M.'],
            ['Akuntansi Manajemen', 'A. Reski Almaida Dg Macenning, S.Pd., M.M.'],
            ['Ekonometrika', 'Prof. Dr. Romansyah Sahabuddin,S.E.,M.Si'],
            ['Penelitian Bisnis', 'Chairunnisaa M Z Huzaen, S.E., M.S.M.'],
            ['Perpajakan', 'Rahmat Burhamzah, S.Pd., M.M., Ak.'],
            ['Ekonomi Manajerial', 'Nulthazam Sarah, S.E., M.M.'],
            ['Kebanksentralan', 'Indri Iswardhani, S.E., M.M.'],
            ['Manajemen Perbankan Syariah', 'Deddy Ibrahim Rauf, S.E., M.M.'],
            ['Analisis Laporan Keuangan', 'Fakhirah Husain, S.E., M.M.'],
            ['Anggaran Perusahaan', 'Andi Dewi Angreyani'],
            ['Lembaga Keuangan Non Bank', 'Nurhaedah, S.E., M.Si.'],
            ['Manajemen Keuangan Koperasi & UMKM', 'Nurhaedah, S.E., M.Si.'],
            ['Studi Kelayakan Keuangan Bisnis', 'Andi Dewi Angreyani'],
            ['Manajemen Keuangan Strategik', 'Fitriani Rahim, S.E., M.M.'],
            ['Perilaku Keuangan', 'Andi Dewi Angreyani'],
            ['Financial Technology', 'Muhammad Rijal Alim Rahmat'],
            ['Manajemen dan Kebijakan Keuangan Daerah', 'Nidrah, S.E., M.M.'],
            ['Manajemen Investasi dan Portofolio', 'Indah Lestari Anwar, S.E., M.S.M.'],
            ['Keuangan Internasional', 'Indah Lestari Anwar, S.E., M.S.M.'],
            ['Seminar Manajemen Keuangan', 'Muhammad Rijal Alim Rahmat'],
            ['Pemasaran Digital', 'Mufidatul Azmi, S.M., M.M.'],
            ['Perilaku Konsumen', 'Chairunnisaa M Z Huzaen, S.E., M.S.M.'],
            ['Manajemen Produk dan Merek', 'Mufidatul Azmi, S.M., M.M.'],
            ['Pemasaran Internasional', 'Wiwin Riski Windarsari, S.E., M.M.'],
            ['Manajemen Agribisnis', 'Muh Al Fatah Arief Putra, S.E., M.M.'],
            ['Komunikasi Pemasaran', 'Wiwin Riski Windarsari, S.E., M.M.'],
            ['Pemasaran Jasa', 'Wiwin Riski Windarsari, S.E., M.M.'],
            ['Manajemen Konten', 'Achmad Ridha, S.M., M.M.'],
            ['Manajemen Penjualan', 'Dr. Azlan Azhari, S.E., M.M.'],
            ['Riset Analisis Pasar', 'Dr. Nurul Fadilah Aswar, S.E., M.M.'],
            ['MICE Management', 'Muh Al Fatah Arief Putra, S.E., M.M.'],
            ['Strategi Pemasaran', 'Nur Fadilah Ayu Sandira, S.Pd., M.M.'],
            ['Manajemen Distribusi', 'Andi Aryani Hardiyanti, S.Si., M.M.'],
            ['Seminar Manajemen Pemasaran', 'Wiwin Riski Windarsari, S.E., M.M.'],
            ['Manajemen Talenta', 'Rezky Amalia Hamka, S.E., M.M.'],
            ['Kepemimpinan', 'Josafat Gracia Ginting, S.M., M.M.'],
            ['Sistem Informasi SDM', 'Anang Setiawan, S.M., M.M.'],
            ['Manajemen SDM Strategik', 'Rezky Amalia Hamka, S.E., M.M.'],
            ['Manajemen Kinerja', 'Muh. Nurfadel Hamzah, S.Tr.Pel., M.M.'],
            ['Manajemen Perubahan', 'Ridfan Abadi, S.E., M.M.'],
            ['Pelatihan dan Pengembangan SDM', 'Abdul Muis, S.E., M.M.'],
            ['Kesehatan dan Keselamatan Kerja', 'Widhi Nugraha SD'],
            ['Organizational Development', 'Ridfan Abadi, S.E., M.M.'],
            ['Manajemen Kompensasi', 'Sri Astuti Nasir, S.Pd., M.M.'],
            ['Psikologi Industri', 'Ridfan Abadi, S.E., M.M.'],
            ['Manajemen Konflik dan Hubungan Industrial', 'A. Nurfiana Haz, S.E., M.M.'],
            ['Manajemen SDM Internasional', 'Josafat Gracia Ginting, S.M., M.M.'],
            ['Seminar Manajemen SDM', 'Sri Astuti Nasir, S.Pd.,M.M'],
            ['KKP', 'Rezky Amalia Hamka, S.E., M.M.'],
            ['Tugas Akhir', 'Rezky Amalia Hamka, S.E., M.M.'],
        ];

        foreach ($assignments as [$mkNama, $dosenNama]) {
            $mk = MataKuliah::where('program_studi_id', $psId)
                ->whereRaw('TRIM(nama) = ?', [trim($mkNama)])
                ->first();
            $userId = $dosenMap[$dosenNama] ?? null;
            if ($mk && $userId) {
                $mk->dosens()->syncWithoutDetaching([$userId]);
            } else {
                $this->command->warn("Tidak ditemukan: MK='$mkNama' | Dosen='$dosenNama'");
            }
        }

        $this->command->info('PenanggungjawabSeeder: 84 penugasan, 43 akun dosen.');
    }
}