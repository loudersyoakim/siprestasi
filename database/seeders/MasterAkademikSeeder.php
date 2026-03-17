<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // ==========================================
        // 1. SEEDER FAKULTAS
        // ==========================================
        // Pakai insertGetId agar kita bisa langsung mengambil ID-nya
        $fakultasId = DB::table('fakultas')->insertGetId([
            'kode_fakultas' => '4',
            'nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'singkatan'     => 'FMIPA',
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // ==========================================
        // 2. SEEDER JURUSAN
        // ==========================================
        // Insert Jurusan Matematika & ambil ID-nya untuk Prodi nanti
        $jurusanMatematikaId = DB::table('jurusan')->insertGetId([
            'fakultas_id'  => $fakultasId,
            'nama_jurusan' => 'Matematika',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        // Insert Jurusan lainnya
        DB::table('jurusan')->insert([
            [
                'fakultas_id'  => $fakultasId,
                'nama_jurusan' => 'Biologi',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'fakultas_id'  => $fakultasId,
                'nama_jurusan' => 'Kimia',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'fakultas_id'  => $fakultasId,
                'nama_jurusan' => 'Fisika',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ]);

        // ==========================================
        // 3. SEEDER PRODI
        // ==========================================
        DB::table('prodi')->insert([
            [
                'jurusan_id' => $jurusanMatematikaId,
                'nama_prodi' => 'Ilmu Komputer',
                'jenjang'    => 'S1',
                'kode_prodi' => '50',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
