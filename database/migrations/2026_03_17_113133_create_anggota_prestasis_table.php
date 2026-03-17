<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_prestasis', function (Blueprint $table) {
            $table->id();
            // Relasi ke prestasi (kalau prestasi dihapus, data anggota ikut terhapus)
            $table->foreignId('prestasi_id')->constrained('prestasis')->onDelete('cascade');

            // Relasi ke user/mahasiswa
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Peran dalam tim
            $table->enum('peran', ['Ketua', 'Anggota'])->default('Anggota');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_prestasis');
    }
};
