<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $pengaturan = [
            [
                'kunci' => 'nama_aplikasi',
                'nilai' => 'Siprestasi',
                'deskripsi' => 'Nama aplikasi yang muncul di Landing Page dan Dashboard.'
            ],
            [
                'kunci' => 'logo_aplikasi',
                'nilai' => 'logo-unimed.png',
                'deskripsi' => 'Path file logo aplikasi. Jika kosong, akan menggunakan logo bawaan.'
            ],
            [
                'kunci' => 'kontak_telepon',
                'nilai' => '+6285260269861',
                'deskripsi' => 'Nomor WhatsApp Admin untuk tombol Chat di menu Bantuan.'
            ],
            [
                'kunci' => 'email_kampus',
                'nilai' => 'contohemail@unimed.ac.id',
                'deskripsi' => 'Email resmi yang ditampilkan di sistem.'
            ],
            [
                'kunci' => 'pesan_bantuan',
                'nilai' => 'Jam operasional Admin melayani balasan pesan adalah pada hari kerja (Senin - Jumat) pukul 08:00 - 16:00 WIB.',
                'deskripsi' => 'Pesan informasi jam kerja di halaman Bantuan.'
            ],
            [
                'kunci' => 'deskripsi_landing',
                'nilai' => 'Platform terpadu untuk mencatat setiap pencapaian. Karena setiap prestasi sangat berharga.',
                'deskripsi' => 'Teks deskripsi di bawah judul halaman depan.'
            ],
            [
                'kunci' => 'wajib_aktivasi_mahasiswa',
                'nilai' => '1',
                'deskripsi' => 'Jika 1, akun mahasiswa baru harus diaktivasi Admin agar bisa login.'
            ],
            [
                'kunci' => 'nama_universitas',
                'nilai' => 'Universitas Negeri Medan',
                'deskripsi' => 'Nama institusi kampus.'
            ],
            [
                'kunci' => 'hero_title_1',
                'nilai' => 'Sistem Arsip',
                'deskripsi' => 'Judul utama landing page baris 1.'
            ],
            [
                'kunci' => 'hero_title_2',
                'nilai' => 'Prestasi',
                'deskripsi' => 'Judul utama landing page baris 2 (berwarna hijau).'
            ],
            [
                'kunci' => 'hero_title_3',
                'nilai' => 'Mahasiswa',
                'deskripsi' => 'Judul utama landing page baris 3.'
            ],
        ];

        foreach ($pengaturan as $item) {
            DB::table('pengaturan_sistem')->updateOrInsert(
                ['kunci' => $item['kunci']],
                [
                    'nilai' => $item['nilai'],
                    'deskripsi' => $item['deskripsi'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
