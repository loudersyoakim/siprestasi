<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Mahasiswa yang lapor
            $table->foreignId('kategori_prestasi_id')->constrained('kategori_prestasis'); // Form apa yang diisi

            // Kolom Statis (Wajib Ada)
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision'])->default('pending');
            $table->text('pesan_revisi')->nullable(); // Pesan dari admin jika ditolak/revisi

            // Kolom Dinamis (Jawaban form dibungkus ke dalam JSON)
            $table->json('data_dinamis');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
