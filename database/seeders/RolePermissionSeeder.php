<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['admin', 'kaprodi', 'dosen'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $prodi = ProgramStudi::first();

        $verified = now();

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@kurikulum.id'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'program_studi_id' => $prodi?->id, 'email_verified_at' => $verified]
        );
        $admin->syncRoles(['admin']);

        // Kaprodi
        $kaprodi = User::updateOrCreate(
            ['email' => 'kaprodi@kurikulum.id'],
            ['name' => 'Ketua Program Studi', 'password' => Hash::make('password'), 'program_studi_id' => $prodi?->id, 'jabatan' => 'Ketua Prodi', 'email_verified_at' => $verified]
        );
        $kaprodi->syncRoles(['kaprodi']);

        // Dosen
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@kurikulum.id'],
            ['name' => 'Dosen Demo', 'password' => Hash::make('password'), 'program_studi_id' => $prodi?->id, 'nip' => '198001012010121001', 'email_verified_at' => $verified]
        );
        $dosen->syncRoles(['dosen']);
    }
}
