<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Query Builder di hasPermission

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // =======================================================
    // RELASI DATABASE
    // =======================================================

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    // Prestasi yang disubmit oleh user ini (sebagai Ketua/Pelapor)
    public function prestasis()
    {
        return $this->hasMany(Prestasi::class);
    }

    // Prestasi di mana user ini menjadi anggota tim (Tabel Pivot)
    public function riwayatPrestasi()
    {
        return $this->belongsToMany(Prestasi::class, 'anggota_prestasis', 'user_id', 'prestasi_id')
            ->withPivot('peran')
            ->withTimestamps();
    }


    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    // =======================================================
    // HELPER & LOGIKA SISTEM (RBAC)
    // =======================================================

    // Variabel untuk menyimpan izin sementara agar tidak query database berulang kali (Sangat Cepat!)
    protected $userPermissions = null;

    /**
     * Cek apakah user punya permission tertentu (Sistem RBAC)
     */
    public function hasPermission($permissionCode)
    {
        // Jika user tidak punya role_id, langsung tolak aksesnya
        if (!$this->role_id) {
            return false;
        }

        // Jika array izin masih kosong, ambil dari database 1X SAJA
        if ($this->userPermissions === null) {
            $this->userPermissions = DB::table('role_permissions')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
                ->where('role_permissions.role_id', $this->role_id)
                ->pluck('permissions.kode_permission')
                ->toArray();
        }

        // Cek apakah kode izin yang diminta ada di dalam daftar array milik user
        return in_array($permissionCode, $this->userPermissions);
    }
}
