<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Jenis Prestasi (Contoh: Kompetisi, Non-Kompetisi)
        Schema::create('jenis_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis');
            $table->timestamps();
        });

        // Tabel Kategori Prestasi (Contoh: Sains, Olahraga, Seni)
        Schema::create('kategori_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Tabel Tingkat Prestasi (Contoh: Internasional, Nasional, Provinsi)
        Schema::create('tingkat_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tingkat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atribut_prestasi_tables');
    }
};
