<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            // Kita ubah ke string agar bebas menampung teks apa pun
            $table->string('status', 20)->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            // Jika rollback, balikkan ke ENUM awal (sesuai kebutuhan)
            $table->enum('status', ['Pending', 'Disetujui Jurusan', 'Disetujui Fakultas', 'Ditolak', 'Revisi'])->default('Pending')->change();
        });
    }
};
