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
        Schema::table('kontens', function (Blueprint $table) {
            // 1. Tambahkan kolom prestasi_id (jika belum ada)
            if (!Schema::hasColumn('kontens', 'prestasi_id')) {
                $table->foreignId('prestasi_id')->nullable()->after('id')
                    ->constrained('prestasi')->onDelete('set null');
            }
            $table->string('category')->default('berita')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontens', function (Blueprint $table) {
            // Balikkan perubahan jika migration di-rollback
            $table->dropForeign(['prestasi_id']);
            $table->dropColumn('prestasi_id');

            // Kembalikan tipe data ke enum (opsional)
            $table->enum('category', ['berita', 'lomba', 'informasi', 'pengumuman'])->change();
        });
    }
};
