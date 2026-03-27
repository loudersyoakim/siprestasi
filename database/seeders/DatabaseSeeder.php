<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MasterAkademikSeeder::class, // 1. Buat Fakultas, Jurusan, Prodi dulu
            RbacSeeder::class,           // 2. Buat Role dan Hak Akses (Permission)
            PengaturanSeeder::class,     // 3. Masukkan Pengaturan Web & Landing Page
            UserSeeder::class,           // 4. Baru masukkan User (karena user butuh ID Role & Prodi)
        ]);
    }
}
