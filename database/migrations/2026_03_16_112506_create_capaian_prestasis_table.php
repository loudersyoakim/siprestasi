<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capaian_prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_capaian'); // Juara 1, Harapan, Medali Emas, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capaian_prestasi');
    }
};
