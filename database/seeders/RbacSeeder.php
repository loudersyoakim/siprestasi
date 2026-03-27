<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan tabel agar tidak terjadi duplikasi saat seeder dijalankan ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. DAFTAR ROLES UTAMA
        $rolesData = [
            ['kode_role' => 'SA', 'nama_role' => 'Super Admin', 'deskripsi' => 'Full access teknis (Top Level).'],
            ['kode_role' => 'AD', 'nama_role' => 'Admin', 'deskripsi' => 'Pengelolaan operasional harian.'],
            ['kode_role' => 'FK', 'nama_role' => 'Fakultas', 'deskripsi' => 'Validasi final & laporan tingkat fakultas.'],
            ['kode_role' => 'JR', 'nama_role' => 'Jurusan', 'deskripsi' => 'Validasi tahap awal & laporan tingkat prodi/jurusan.'],
            ['kode_role' => 'MHS', 'nama_role' => 'Mahasiswa', 'deskripsi' => 'Self-service layanan akademik (input prestasi, surat).'],
        ];

        $roleIds = [];
        foreach ($rolesData as $data) {
            $roleIds[$data['kode_role']] = DB::table('roles')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        // 3. DAFTAR PERMISSIONS (Hierarki Top-Down Sesuai Permintaan)
        $permissionsData = [

            // ================== DASHBOARD ==================
            ['modul' => 'dashboard', 'kode' => 'dashboard.view_global', 'label' => 'Akses Dashboard Utama', 'roles' => ['SA', 'AD']],
            ['modul' => 'dashboard', 'kode' => 'dashboard.view_fakultas', 'label' => 'Akses Dashboard Fakultas', 'roles' => ['SA', 'AD', 'FK']],
            ['modul' => 'dashboard', 'kode' => 'dashboard.view_jurusan', 'label' => 'Akses Dashboard Jurusan', 'roles' => ['SA', 'AD', 'FK', 'JR']],
            // Khusus MHS (Dashboard Pribadi)
            ['modul' => 'dashboard', 'kode' => 'dashboard.view_pribadi', 'label' => 'Akses Dashboard Mahasiswa', 'roles' => ['MHS']],

            // ================== MAHASISWA & SELF SERVICE ==================
            // Hal-hal yang KHUSUS MHS, Super Admin tidak perlu punya karena ini self-service
            ['modul' => 'prestasi', 'kode' => 'prestasi.view_own', 'label' => 'Lihat Riwayat Prestasi Pribadi', 'roles' => ['MHS']],
            ['modul' => 'surat', 'kode' => 'surat.view_own', 'label' => 'Lihat Riwayat Surat Pribadi', 'roles' => ['MHS']],
            ['modul' => 'surat', 'kode' => 'surat.create', 'label' => 'Ajukan Permohonan Surat Baru', 'roles' => ['MHS']],

            // ================== INPUT PRESTASI (BISA SEMUA) ==================
            // SA, AD, FK, JR juga bisa input prestasi (bantu input atau delegasi)
            ['modul' => 'prestasi', 'kode' => 'prestasi.create', 'label' => 'Input / Lapor Prestasi Baru', 'roles' => ['SA', 'AD', 'FK', 'JR', 'MHS']],

            // ================== AKSES PUBLIK ==================
            ['modul' => 'konten', 'kode' => 'konten.view_public', 'label' => 'Lihat Informasi & Pengumuman', 'roles' => ['SA', 'AD', 'FK', 'JR', 'MHS']],

            // ================== MANAJEMEN AKUN ==================
            ['modul' => 'akun', 'kode' => 'akun.view_list', 'label' => 'Akses Menu Manajemen Akun', 'roles' => ['SA', 'AD']],
            ['modul' => 'akun', 'kode' => 'akun.manage_user', 'label' => 'Tambah / Edit / Hapus Pengguna', 'roles' => ['SA', 'AD']],
            // Khusus Super Admin
            ['modul' => 'akun', 'kode' => 'akun.manage_role', 'label' => 'Kelola Hak Akses & Role (RBAC)', 'roles' => ['SA']],

            // ================== MANAJEMEN PRESTASI (Hierarki FK/JR) ==================
            ['modul' => 'prestasi', 'kode' => 'prestasi.view_all', 'label' => 'Lihat Semua Data Prestasi', 'roles' => ['SA', 'AD', 'FK', 'JR']],
            ['modul' => 'prestasi', 'kode' => 'prestasi.validate', 'label' => 'Validasi / Tolak Prestasi Masuk', 'roles' => ['SA', 'AD', 'FK', 'JR']],
            // Config khusus teknis
            ['modul' => 'prestasi', 'kode' => 'prestasi.config_form', 'label' => 'Kelola Form Kategori Prestasi', 'roles' => ['SA', 'AD']],
            ['modul' => 'prestasi', 'kode' => 'prestasi.config_workflow', 'label' => 'Ubah Alur Persetujuan Prestasi', 'roles' => ['SA']],

            // ================== MANAJEMEN SURAT ==================
            ['modul' => 'surat', 'kode' => 'surat.view_all', 'label' => 'Lihat Semua Data Arsip Surat', 'roles' => ['SA', 'AD', 'FK']],
            ['modul' => 'surat', 'kode' => 'surat.process', 'label' => 'Proses & Validasi Permohonan Surat', 'roles' => ['SA', 'AD', 'FK']],
            // Config khusus teknis
            ['modul' => 'surat', 'kode' => 'surat.config_template', 'label' => 'Buat & Edit Template Dokumen', 'roles' => ['SA', 'AD']],
            ['modul' => 'surat', 'kode' => 'surat.config_workflow', 'label' => 'Ubah Alur Tanda Tangan Surat', 'roles' => ['SA']],

            // ================== MANAJEMEN KONTEN ==================
            ['modul' => 'konten', 'kode' => 'konten.manage_artikel', 'label' => 'Kelola Berita & Galeri Mading', 'roles' => ['SA', 'AD']],
            ['modul' => 'konten', 'kode' => 'konten.publish_prestasi', 'label' => 'Rilis Publikasi Prestasi', 'roles' => ['SA', 'AD']],

            // ================== MASTER DATA ==================
            ['modul' => 'master', 'kode' => 'master.akademik', 'label' => 'Kelola Struktur Akademik (Fakultas, Prodi)', 'roles' => ['SA', 'AD']],
            ['modul' => 'master', 'kode' => 'master.referensi', 'label' => 'Kelola Referensi (Tingkatan, Jenis)', 'roles' => ['SA', 'AD']],

            // ================== LAPORAN & REKAP ==================
            ['modul' => 'laporan', 'kode' => 'laporan.generate', 'label' => 'Akses Fitur Laporan & Export', 'roles' => ['SA', 'AD', 'FK', 'JR']],

            // ================== PENGATURAN SISTEM ==================
            // Khusus Super Admin
            ['modul' => 'sistem', 'kode' => 'sistem.config', 'label' => 'Kelola Pengaturan Web & Logo', 'roles' => ['SA']],
        ];

        // 4. EKSEKUSI INSERT PERMISSION & PIVOT TABLE
        foreach ($permissionsData as $p) {

            // Insert ke tabel Permissions
            $permId = DB::table('permissions')->insertGetId([
                'modul'           => $p['modul'],
                'kode_permission' => $p['kode'],
                'label'           => $p['label'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Assign izin hanya kepada Role yang tercantum di array 'roles'
            foreach ($p['roles'] as $kodeRole) {
                if (isset($roleIds[$kodeRole])) {
                    DB::table('role_permissions')->insert([
                        'role_id'       => $roleIds[$kodeRole],
                        'permission_id' => $permId
                    ]);
                }
            }
        }
    }
}
