<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'name'     => 'Super Administrator',
            'nim_nip'  => 'superadmin',
            'email'    => 'superadmin@unimed.ac.id',
            'role'     => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        // 2. Akun Admin
        User::create([
            'name'     => 'Admin Universitas',
            'nim_nip'  => 'admin',
            'email'    => 'admin@unimed.ac.id',
            'role'     => 'admin',
            'password' => Hash::make('password'),
        ]);

        // 3. Akun Wakil Dekan
        User::create([
            'name'     => 'Wakil Dekan III',
            'nim_nip'  => 'wakildekan',
            'email'    => 'wakildekan@unimed.ac.id',
            'role'     => 'wakil_dekan',
            'password' => Hash::make('password'),
        ]);

        // 4. Akun Kepala Jurusan
        User::create([
            'name'     => 'Kepala Jurusan',
            'nim_nip'  => 'jurusan',
            'email'    => 'jurusan@unimed.ac.id',
            'role'     => 'jurusan',
            'password' => Hash::make('password'),
        ]);

        // 5. Akun Mahasiswa
        User::create([
            'name'     => 'Mahasiswa Berprestasi',
            'nim_nip'  => 'mahasiswa',
            'email'    => 'mahasiswa@unimed.ac.id',
            'role'     => 'mahasiswa',
            'password' => Hash::make('password'),
        ]);
    }
}
