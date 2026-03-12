<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_user', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel prestasi
            $table->foreignId('prestasi_id')->constrained('prestasi')->onDelete('cascade');
            // Hubungkan ke tabel users (mahasiswa)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_user');
    }
};
