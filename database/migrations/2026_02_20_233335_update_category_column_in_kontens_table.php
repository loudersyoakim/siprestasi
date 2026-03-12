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
            $table->string('category')->default('berita');
        });
    }

    public function down(): void
    {
        Schema::table('kontens', function (Blueprint $table) {
            // Kembalikan ke semula jika diperlukan
            $table->string('category')->change();
        });
    }
};
