<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mengisi Tabel Pengaturan Sistem
        DB::table('pengaturan_sistem')->updateOrInsert(
            ['kunci' => 'wajib_aktivasi_mahasiswa'],
            [
                'nilai' => '1',
                'deskripsi' => 'Jika diaktifkan (1), setiap mahasiswa harus diaktivasi manual oleh Admin.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ambil ID Prodi Ilmu Komputer untuk akun kamu
        $prodiIlkom = Prodi::where('nama_prodi', 'Ilmu Komputer')->first();

        // 2. Data Users
        $users = [
            [
                'name'      => 'Super Administrator',
                'nim_nip'   => 'superadmin',
                'email'     => 'superadmin@unimed.ac.id',
                'role'      => 'super_admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Admin Universitas',
                'nim_nip'   => 'admin',
                'email'     => 'admin@unimed.ac.id',
                'role'      => 'admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Wakil Dekan',
                'nim_nip'   => 'wakildekan',
                'email'     => 'wakildekan@unimed.ac.id',
                'role'      => 'wakil_dekan',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Kepala Jurusan',
                'nim_nip'   => 'jurusan',
                'email'     => 'jurusan@unimed.ac.id',
                'role'      => 'jurusan',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Mahasiswa Berprestasi',
                'nim_nip'   => 'mahasiswa',
                'email'     => 'mahasiswa@unimed.ac.id',
                'role'      => 'mahasiswa',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'LOUDERS YOAKIM TELAUMBANUA',
                'nim_nip'   => '4223250023',
                'email'     => 'louders@unimed.ac.id',
                'role'      => 'mahasiswa',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        // 3. Looping untuk Simpan (PENTING!)
        foreach ($users as $userData) {
            $user = User::create($userData);
        }
    }
}
