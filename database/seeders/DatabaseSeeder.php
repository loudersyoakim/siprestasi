<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            'admin'     => '100',
            'wd'        => '200',
            'kajur'     => '300',
            'gpm'       => '400',
            'mahasiswa' => '4223250023',
        ];

        foreach ($roles as $role => $id) {
            \App\Models\User::factory()->create([
                'name' => "User$role",
                'nim_nip' => "$id",
                'email' => "$role@test.com",
                'password' => bcrypt('password'),
                'role' => $role,
            ]);
        }
    }
}
