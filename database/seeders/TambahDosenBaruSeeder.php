<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Tambah dosen dari file "Daftar Email Institusi Dosen PS Manajemen FEB UNM.xlsx"
 * yang belum ada di sistem.
 *
 * - Email diambil dari Excel bila tersedia.
 * - Bila Excel tidak mencantumkan email, email DIGENERATE (@unm.ac.id) dan
 *   ditandai $generated => true (perlu diverifikasi manual nanti).
 *
 * Idempotent (updateOrCreate by email). Aman dijalankan berkali-kali.
 * Jalankan: php artisan db:seed --class=TambahDosenBaruSeeder
 */
class TambahDosenBaruSeeder extends Seeder
{
    public function run(): void
    {
        $prodi = ProgramStudi::where('kode', 'MNJ-S1')->first();
        $psId  = $prodi?->id;

        // [nama, email, generated?]
        $dosens = [
            // ── Email tersedia di Excel ──────────────────────────────
            ['Prof. Dr. Chalid Imran Musa, M. Si.', 'chalid.imran.musa@unm.ac.id', false],
            ['Prof. Dr. Anwar Ramli, S.E., M. Si.', 'anwar.r@unm.ac.id', false],
            ['Prof. M. Ikhwan Maulana Haeruddin, S.E., MHRMgt., Ph. D.', 'ikhwan.maulana@unm.ac.id', false],
            ['Dr. Agung Widhi Kurniawan, S.T., M.M', 'agungwk@unm.ac.id', false],
            ['Dr. Anwar, S.E., M.Si', 'anwar@unm.ac.id', false],
            ['Dr. Muh. Ichwan Musa, S.E., M. Si.', 'm.ichwan.musa@unm.ac.id', false],
            ['Dr. Muhammad Ilham Wardhana Haeruddin, S.E., MMktMgt.', 'ilham.wardhana@unm.ac.id', false],
            ['Tenri Sayu Puspitaningsih Dipoatmodjo, S.E., M.M', 'tenri.sayu@unm.ac.id', false],
            ['Andi Mustika Amin, S.E., M.Si', 'andimustika@unm.ac.id', false],
            ['Rahmat Riwayat Abadi, S.E., M.M.', 'rahmat.riwayat.abadi@unm.ac.id', false],
            ['Khaidir Syahrul, S.E., M.B.A.', 'khaidir.syahrul@unm.ac.id', false],
            ['Ahmad Rais Habib, S.E., M.M', 'ahmad.raishabib@unm.ac.id', false],

            // ── Email DIGENERATE (tidak ada di Excel) — perlu verifikasi ──
            ['Dr. Burhanuddin, S. Sos., S.E., M.M.', 'burhanuddin@unm.ac.id', true],
            ['Dr. Uhud Darmawan Natsir, S.E., M.M.', 'uhud.darmawan@unm.ac.id', true],
            ['Nurman, S.E., M. Si.', 'nurman@unm.ac.id', true],
            ['Dr. Hety Budiyanti, S.E., M. Ak.', 'hety.budiyanti@unm.ac.id', true],
            ['Dr. Zainal Ruma, S. Pd., M.M.', 'zainal.ruma@unm.ac.id', true],
            ['Dr. Isma Azis Riu, S.P., M.M.', 'isma.azis@unm.ac.id', true],
            ['Annisa Paramaswary Aslam, S.E., M.SM', 'annisa.paramaswary@unm.ac.id', true],
            ['Ilma Wulansari Hasdiansa, M.M.', 'ilma.wulansari@unm.ac.id', true],
            ['Asniwati, S.E., M.Si.', 'asniwati@unm.ac.id', true],
            ['Muh. Yushar Mustafa, S.E., M.Sc.', 'muh.yushar@unm.ac.id', true],
            ['Dr. Kristina Parinsi, S.E., M.Pd', 'kristina.parinsi@unm.ac.id', true],
            ['Dr. Dwi Anugrah Lestari Musa, S.E., M.M', 'dwi.anugrah@unm.ac.id', true],
        ];

        $created   = 0;
        $existed   = 0;
        $generated = [];

        foreach ($dosens as [$name, $email, $isGenerated]) {
            $exists = User::where('email', $email)->exists();

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

            if ($exists) {
                $existed++;
            } else {
                $created++;
                $this->command->info("  + {$name} <{$email}>" . ($isGenerated ? '  [email digenerate]' : ''));
            }

            if ($isGenerated) {
                $generated[] = "{$name} → {$email}";
            }
        }

        $this->command->line('');
        $this->command->info("Selesai. Dibuat: {$created} | Sudah ada: {$existed}");

        if (!empty($generated)) {
            $this->command->line('');
            $this->command->warn('Email berikut DIGENERATE — harap verifikasi & ganti bila perlu:');
            foreach ($generated as $g) {
                $this->command->line("  - {$g}");
            }
        }
    }
}
