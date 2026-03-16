<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Mengisi Tabel Pengaturan Sistem
        DB::table('pengaturan_sistem')->insert([
            [
                'kunci' => 'wajib_aktivasi_mahasiswa',
                'nilai' => '1', // 1 berarti Ya (Wajib), 0 berarti Tidak
                'deskripsi' => 'Jika diaktifkan (1), setiap mahasiswa yang mendaftar harus diaktivasi manual oleh Admin sebelum bisa login.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Mengisi Tabel Users
        $users = [
            [
                'name'     => 'Super Administrator',
                'nim_nip'  => 'superadmin',
                'email'    => 'superadmin@unimed.ac.id',
                'role'     => 'super_admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'     => 'Admin Universitas',
                'nim_nip'  => 'admin',
                'email'    => 'admin@unimed.ac.id',
                'role'     => 'admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'     => 'Wakil Dekan',
                'nim_nip'  => 'wakildekan',
                'email'    => 'wakildekan@unimed.ac.id',
                'role'     => 'wakil_dekan',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'     => 'Kepala Jurusan',
                'nim_nip'  => 'jurusan',
                'email'    => 'jurusan@unimed.ac.id',
                'role'     => 'jurusan',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'     => 'Mahasiswa Berprestasi',
                'nim_nip'  => 'mahasiswa',
                'email'    => 'mahasiswa@unimed.ac.id',
                'role'     => 'mahasiswa',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
