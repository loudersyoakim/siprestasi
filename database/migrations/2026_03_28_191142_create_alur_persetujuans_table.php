<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alur_persetujuans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_role'); // JR, FK, AD
            $table->string('nama_tahapan'); // Validasi Jurusan, dll
            $table->integer('urutan')->default(0); // 1, 2, 3
            $table->boolean('is_active')->default(true);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert Default Data (Biar nggak usah bikin Seeder lagi)
        DB::table('alur_persetujuans')->insert([
            [
                'kode_role' => 'JR',
                'nama_tahapan' => 'Validasi Tingkat Jurusan / Prodi',
                'urutan' => 1,
                'is_active' => true,
                'keterangan' => 'Pengecekan kebenaran data dan kesesuaian prodi mahasiswa.'
            ],
            [
                'kode_role' => 'FK',
                'nama_tahapan' => 'Validasi Tingkat Fakultas',
                'urutan' => 2,
                'is_active' => true,
                'keterangan' => 'Persetujuan Dekanat untuk klaim IKU Fakultas.'
            ],
            [
                'kode_role' => 'AD',
                'nama_tahapan' => 'Validasi Akhir Universitas (Pusat)',
                'urutan' => 3,
                'is_active' => true,
                'keterangan' => 'Persetujuan akhir oleh Kemahasiswaan Universitas untuk publikasi.'
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('alur_persetujuans');
    }
};
