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
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prestasi');
            $table->foreignId('tingkat_id')->constrained('tingkat_prestasi');
            $table->foreignId('jenis_id')->constrained('jenis_prestasi');
            $table->foreignId('kategori_id')->constrained('kategori_prestasi');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik');
            $table->string('sertifikat');
            $table->date('tanggal_peroleh');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_ditolak')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
