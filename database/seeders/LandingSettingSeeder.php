<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'nama_aplikasi', 'label' => 'Nama Aplikasi', 'value' => 'SIARPRESTASI UNIMED', 'is_active' => true, 'type' => 'text'],
            ['key' => 'hero_title_1', 'label' => 'Hero Baris 1', 'value' => 'Sistem Arsip', 'is_active' => true, 'type' => 'text'],
            ['key' => 'hero_title_2', 'label' => 'Hero Baris 2 (Hijau)', 'value' => 'Prestasi', 'is_active' => true, 'type' => 'text'],
            ['key' => 'hero_title_3', 'label' => 'Hero Baris 3', 'value' => 'Mahasiswa', 'is_active' => true, 'type' => 'text'],
            ['key' => 'deskripsi_landing', 'label' => 'Deskripsi Landing', 'value' => 'Platform terpadu untuk mencatat setiap pencapaian luar biasa mahasiswa secara real-time.', 'is_active' => true, 'type' => 'text'],

            // Pengaturan Widget Leaderboard
            ['key' => 'show_leaderboard', 'label' => 'Tampilkan Leaderboard', 'value' => '3', 'is_active' => true, 'type' => 'number'],

            // Pengaturan Widget Statistik
            ['key' => 'show_statistics', 'label' => 'Tampilkan Statistik IKU', 'value' => null, 'is_active' => true, 'type' => 'toggle'],
            ['key' => 'stat_title', 'label' => 'Judul Statistik', 'value' => 'Sebaran Prestasi Fakultas', 'is_active' => true, 'type' => 'text'],
            ['key' => 'stat_type', 'label' => 'Tipe Grafik Statistik', 'value' => 'doughnut', 'is_active' => true, 'type' => 'text'],
        ];

        DB::table('landing_settings')->insert($settings);
    }
}
