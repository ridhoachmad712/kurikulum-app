<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CloTaxonomiSeeder::class,
            ProgramStudiSeeder::class,
            RolePermissionSeeder::class,
            MkCplSeeder::class,
            PenanggungjawabSeeder::class,
        ]);
    }
}
