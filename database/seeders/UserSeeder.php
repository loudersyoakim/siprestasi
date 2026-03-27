<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID untuk relasi
        $roleSA  = DB::table('roles')->where('kode_role', 'SA')->value('id');
        $roleAD  = DB::table('roles')->where('kode_role', 'AD')->value('id');
        $roleFK  = DB::table('roles')->where('kode_role', 'FK')->value('id');
        $roleJR  = DB::table('roles')->where('kode_role', 'JR')->value('id');
        $roleMHS = DB::table('roles')->where('kode_role', 'MHS')->value('id');
        $prodiIlkom = DB::table('prodi')->where('nama_prodi', 'Ilmu Komputer')->value('id');

        $users = [
            ['name' => 'Super Administrator', 'nim_nip' => 'superadmin', 'email' => 'sa@unimed.ac.id', 'role_id' => $roleSA, 'prodi_id' => null],
            ['name' => 'Admin Universitas', 'nim_nip' => 'admin', 'email' => 'admin@unimed.ac.id', 'role_id' => $roleAD, 'prodi_id' => null],
            ['name' => 'FMIPA', 'nim_nip' => 'fakultas', 'email' => 'fk@unimed.ac.id', 'role_id' => $roleFK, 'prodi_id' => null],
            ['name' => 'Jurusan', 'nim_nip' => 'jurusan', 'email' => 'jr@unimed.ac.id', 'role_id' => $roleJR, 'prodi_id' => null],
            ['name' => 'LOUDERS YOAKIM T', 'nim_nip' => '4223250023', 'email' => 'louders@unimed.ac.id', 'role_id' => $roleMHS, 'prodi_id' => $prodiIlkom, 'nomor_hp' => '081234567890'],
        ];

        foreach ($users as $u) {
            DB::table('users')->insert([
                'name' => $u['name'],
                'nim_nip' => $u['nim_nip'],
                'email' => $u['email'],
                'nomor_hp' => $u['nomor_hp'] ?? null,
                'role_id' => $u['role_id'],
                'prodi_id' => $u['prodi_id'],
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
