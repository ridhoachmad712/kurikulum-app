<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Update email dosen dari @kurikulum.id ke email institusi @unm.ac.id
 * berdasarkan file "Daftar Email Institusi Dosen PS Manajemen FEB UNM.xlsx".
 *
 * Pencocokan berdasarkan EMAIL LAMA (bukan ID) agar aman dijalankan
 * di environment manapun. Idempotent — aman dijalankan berkali-kali.
 *
 * Jalankan: php artisan db:seed --class=UpdateDosenEmailSeeder
 */
class UpdateDosenEmailSeeder extends Seeder
{
    public function run(): void
    {
        // email_lama => email_baru
        $map = [
            'rezky.amalia@kurikulum.id'    => 'rezky.amalia.hamka@unm.ac.id',
            'abdul.rahman@kurikulum.id'    => 'abdul.rahman1582@unm.ac.id',
            'azlan.azhari@kurikulum.id'    => 'azlan.azhari@unm.ac.id',
            'muhammad.rijal@kurikulum.id'  => 'muhammadrijalalim@unm.ac.id',
            'a.reski@kurikulum.id'         => 'reski.almaida@unm.ac.id',
            'ridwan.andi@kurikulum.id'     => 'ridwan.andi.mattoliang@unm.ac.id',
            'rostina.alimuddin@kurikulum.id' => 'rostina@unm.ac.id',
            'indri.iswardhani@kurikulum.id' => 'indri.iswardhani@unm.ac.id',
            'anang.setiawan@kurikulum.id'  => 'anang.setiawan@unm.ac.id',
            'rahmat.burhamzah@kurikulum.id' => 'rahmat.burhamzah@unm.ac.id',
            'wiwin.riski@kurikulum.id'     => 'wiwin.riski.windarsari@unm.ac.id',
            'ushwa.dwi@kurikulum.id'       => 'ushwa.dwi.masrurah@unm.ac.id',
            'indah.lestari@kurikulum.id'   => 'indahlestarianwar@unm.ac.id',
            'fitriani.rahim@kurikulum.id'  => 'fitriani.rahim@unm.ac.id',
            'muhaidir.ikram@kurikulum.id'  => 'muhaidir.ikram@unm.ac.id',
            'mufidatul.azmi@kurikulum.id'  => 'mufidatulazmi@unm.ac.id',
            'nidrah@kurikulum.id'          => 'nidrah@unm.ac.id',
            'josafat.gracia@kurikulum.id'  => 'josafat.gracia.ginting@unm.ac.id',
            'muh.nurfadel@kurikulum.id'    => 'muhnurfadel@unm.ac.id',
            'ade.vidya@kurikulum.id'       => 'ade.vidya.eryanti@unm.ac.id',
            'a.nurfiana@kurikulum.id'      => 'andinurfianahaz@unm.ac.id',
            'rika.kurniawati@kurikulum.id' => 'rika.kurniawati@unm.ac.id',
            'andi.aryani@kurikulum.id'     => 'andiaryani@unm.ac.id',
            'fakhirah.husain@kurikulum.id' => 'fakhirah.husain@unm.ac.id',
            'muh.al@kurikulum.id'          => 'mualfattah@unm.ac.id',
            'dr.romansyah@kurikulum.id'    => 'romansyah@unm.ac.id',
            'chairunnisaa.m@kurikulum.id'  => 'chairunnisaa@unm.ac.id',
            'nurhaedah@kurikulum.id'       => 'nurhaedahedha@unm.ac.id',
            'ridfan.abadi@kurikulum.id'    => 'ridfan.rifadly.abadi@unm.ac.id',
        ];

        $updated = 0;
        $skipped = 0;

        foreach ($map as $old => $new) {
            $user = User::where('email', $old)->first();

            // Sudah diupdate sebelumnya (idempotent)
            if (!$user) {
                if (User::where('email', $new)->exists()) {
                    $this->command->line("  - Lewati: {$new} (sudah terpasang)");
                } else {
                    $this->command->warn("  ! Tidak ditemukan: {$old}");
                }
                $skipped++;
                continue;
            }

            // Cegah duplikat email
            if (User::where('email', $new)->where('id', '!=', $user->id)->exists()) {
                $this->command->error("  x Bentrok: {$new} sudah dipakai user lain — {$old} dilewati");
                $skipped++;
                continue;
            }

            $user->email = $new;
            $user->save();
            $updated++;
            $this->command->info("  ok {$old} -> {$new}");
        }

        $this->command->line('');
        $this->command->info("Selesai. Diupdate: {$updated} | Dilewati: {$skipped}");
    }
}
