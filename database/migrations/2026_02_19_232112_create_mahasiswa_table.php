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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nim')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->year('angkatan');

            // Relasi ke struktur kampus yang sudah Abang buat
            $table->foreignId('prodi_id')->constrained('prodi');
            $table->foreignId('jurusan_id')->constrained('jurusan');
            $table->foreignId('fakultas_id')->constrained('fakultas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
