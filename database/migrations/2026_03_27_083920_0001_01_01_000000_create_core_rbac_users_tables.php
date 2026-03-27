<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. STRUKTUR AKADEMIK
        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fakultas', 5)->unique();
            $table->string('nama_fakultas');
            $table->string('singkatan')->nullable();
            $table->timestamps();
        });

        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fakultas_id')->constrained('fakultas')->onDelete('cascade');
            $table->string('nama_jurusan');
            $table->timestamps();
        });

        Schema::create('prodi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->constrained('jurusan')->onDelete('cascade');
            $table->string('kode_prodi', 10)->unique();
            $table->string('nama_prodi');
            $table->string('jenjang', 5);
            $table->timestamps();
        });

        // 2. STRUKTUR RBAC (ROLES & PERMISSIONS)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('kode_role', 20)->unique(); // Cth: SA, AD, FK, JR, MHS
            $table->string('nama_role');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('modul'); // Cth: dashboard, user, akademik
            $table->string('kode_permission')->unique(); // Cth: prestasi.create
            $table->string('label'); // Cth: Submit pengajuan prestasi baru
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // 3. TABEL USERS (Digabung dengan Mahasiswa + No HP)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim_nip')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('nomor_hp', 20)->nullable(); // Request tambahan nomor HP
            $table->string('password');

            // Relasi ke RBAC & Akademik
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('prodi')->nullOnDelete();

            $table->boolean('is_active')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. PENGATURAN SISTEM & BAWAAN LARAVEL
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->string('nilai');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('pengaturan_sistem');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('prodi');
        Schema::dropIfExists('jurusan');
        Schema::dropIfExists('fakultas');
    }
};
