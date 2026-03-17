<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_form_prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_prestasi_id')->constrained('kategori_prestasis')->cascadeOnDelete();

            $table->string('nama_field');
            $table->string('label');
            $table->enum('tipe', ['text', 'number', 'date', 'select', 'file', 'textarea', 'anggota_kelompok']);

            $table->json('opsi')->nullable();
            $table->boolean('is_required')->default(true);
            $table->string('keterangan')->nullable();
            $table->integer('urutan')->default(0);

            $table->json('aturan_validasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_form_prestasis');
    }
};
