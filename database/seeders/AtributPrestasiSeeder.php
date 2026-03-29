<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TingkatPrestasi;
use App\Models\CapaianPrestasi;
use Illuminate\Support\Facades\Schema;

class AtributPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan tabel agar tidak duplikat saat di-seed ulang
        Schema::disableForeignKeyConstraints();
        TingkatPrestasi::truncate();
        CapaianPrestasi::truncate();
        Schema::enableForeignKeyConstraints();

        // ========================================================
        // DATA TINGKAT PRESTASI (Sesuai Screenshot 1)
        // ========================================================
        $dataTingkat = [
            'Kota',
            'Provinsi',
            'Wilayah',
            'Nasional',
            'Internasional',
        ];

        foreach ($dataTingkat as $tingkat) {
            TingkatPrestasi::create([
                'nama_tingkat' => $tingkat,
                'is_active' => true,
            ]);
        }

        // ========================================================
        // DATA CAPAIAN PRESTASI (Sesuai Screenshot 2)
        // ========================================================
        $dataCapaian = [
            'Juara I',
            'Juara II',
            'Juara III',
            'Harapan I',
            'Harapan II',
            'Harapan III',
            'Apresiasi Kejuaraan / Penghargaan',
            'Peserta',
        ];

        foreach ($dataCapaian as $capaian) {
            CapaianPrestasi::create([
                'nama_capaian' => $capaian,
                'is_active' => true,
            ]);
        }
    }
}
