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
        // Tabel Tahun Akademik (Cukup 1 row biasanya, atau list tahun)
        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // Contoh: 2023/2024
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Template Surat
        Schema::create('template_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template'); // Laporan atau Rekap
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_sta_tables');
    }
};
