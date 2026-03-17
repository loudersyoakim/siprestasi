<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            // Kolom khusus untuk kebutuhan publikasi Manajemen Konten
            $table->string('foto_kegiatan')->nullable()->after('data_dinamis');
            $table->text('cerita_kegiatan')->nullable()->after('foto_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            $table->dropColumn(['foto_kegiatan', 'cerita_kegiatan']);
        });
    }
};
