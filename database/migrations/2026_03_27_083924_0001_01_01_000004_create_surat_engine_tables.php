<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_surat');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->longText('isi_template')->nullable();
            $table->string('prefix_nomor')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('field_template_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_surat_id')->constrained('template_surats')->onDelete('cascade');
            $table->string('nama_field');
            $table->string('label');
            $table->string('placeholder_key');
            $table->enum('tipe', ['text', 'textarea', 'date', 'select']);
            $table->json('opsi')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('permohonan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('template_surat_id')->constrained('template_surats')->onDelete('cascade');
            $table->string('nomor_surat')->nullable();
            $table->json('data_isian')->nullable();
            $table->enum('status', ['Pending', 'Diproses Admin', 'Menunggu TTD', 'Selesai', 'Ditolak'])->default('Pending');
            $table->string('file_pdf')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_surats');
        Schema::dropIfExists('field_template_surats');
        Schema::dropIfExists('template_surats');
    }
};
