<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =======================================================
        // MASTER DATA TINGKAT & CAPAIAN
        // =======================================================

        // 1. TABEL MASTER TINGKAT PRESTASI
        Schema::create('tingkat_prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tingkat')->unique()->comment('Contoh: Nasional, Internasional, Wilayah');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. TABEL MASTER CAPAIAN PRESTASI
        Schema::create('capaian_prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_capaian')->unique()->comment('Contoh: Juara 1, Medali Emas, Lulus Sertifikasi');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // =======================================================
        // MASTER FORM & FIELD DINAMIS
        // =======================================================

        // 3. TABEL FORM PRESTASI (MASTER FORM)
        Schema::create('form_prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_form');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        // 4. TABEL FIELD FORM PRESTASI (BUILDER DINAMIS)
        Schema::create('field_form_prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_prestasi_id')->constrained('form_prestasis')->onDelete('cascade');
            $table->string('nama_field');
            $table->string('label');
            $table->enum('tipe', ['text', 'textarea', 'number', 'date', 'file', 'select', 'radio', 'checkbox', 'anggota_kelompok']);
            $table->json('opsi')->nullable();
            $table->json('aturan_validasi')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // =======================================================
        // DATA TRANSAKSI UTAMA
        // =======================================================

        // 5. TABEL PRESTASI UTAMA (GABUNGAN SEMUA REVISI)
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_prestasi_id')->constrained('form_prestasis')->onDelete('cascade');

            // --- KOLOM STATIS & RELASI MASTER DATA ---
            $table->string('nama_kegiatan')->nullable()->comment('Nama Kegiatan/Lomba/Sertifikasi');
            $table->year('tahun_kegiatan')->nullable();

            // Menggunakan Foreign Key ke tabel Master
            $table->foreignId('tingkat_prestasi_id')->nullable()->constrained('tingkat_prestasis')->nullOnDelete();
            $table->foreignId('capaian_prestasi_id')->nullable()->constrained('capaian_prestasis')->nullOnDelete();

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // --- STATUS & PUBLIKASI ---
            $table->string('status', 20)->default('Pending');
            $table->boolean('is_published')->default(false);
            $table->text('deskripsi_kegiatan')->nullable()->comment('Deskripsi singkat untuk Landing Page');
            $table->string('thumbnail_berita')->nullable()->comment('Cover khusus berita/publikasi');

            // --- KOLOM DINAMIS & BUKTI FISIK ---
            $table->json('data_dinamis')->nullable();
            $table->text('pesan_revisi')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->text('cerita_kegiatan')->nullable();

            $table->timestamps();
        });

        // 6. TABEL PIVOT ANGGOTA KELOMPOK
        Schema::create('anggota_prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('peran', ['Ketua', 'Anggota'])->default('Anggota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Harus di-drop dari yang punya Foreign Key (Child) ke atas (Parent)
        Schema::dropIfExists('anggota_prestasis');
        Schema::dropIfExists('prestasis');
        Schema::dropIfExists('field_form_prestasis');
        Schema::dropIfExists('form_prestasis');
        Schema::dropIfExists('capaian_prestasis'); // Drop Master Data
        Schema::dropIfExists('tingkat_prestasis'); // Drop Master Data
    }
};
