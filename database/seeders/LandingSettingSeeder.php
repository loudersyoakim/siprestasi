<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
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
