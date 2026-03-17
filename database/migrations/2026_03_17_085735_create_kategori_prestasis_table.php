<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Cth: Pelaporan Prestasi Diluar Kegiatan Belmawa
            $table->text('deskripsi')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_prestasis');
    }
};
