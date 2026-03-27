<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('field_form_prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_prestasi_id')->constrained('form_prestasis')->onDelete('cascade');
            $table->string('nama_field');
            $table->string('label');
            $table->enum('tipe', ['text', 'textarea', 'number', 'date', 'file', 'select', 'anggota_kelompok']);
            $table->json('opsi')->nullable();
            $table->json('aturan_validasi')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_prestasi_id')->constrained('form_prestasis')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Disetujui Jurusan', 'Disetujui Fakultas', 'Ditolak', 'Revisi'])->default('Pending');
            $table->json('data_dinamis')->nullable();
            $table->text('pesan_revisi')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->text('cerita_kegiatan')->nullable();
            $table->timestamps();
        });

        // Tabel Pivot Anggota Kelompok
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
        Schema::dropIfExists('anggota_prestasis');
        Schema::dropIfExists('prestasis');
        Schema::dropIfExists('field_form_prestasis');
        Schema::dropIfExists('form_prestasis');
    }
};
