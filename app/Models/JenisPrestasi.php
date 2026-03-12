<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPrestasi extends Model
{
    use HasFactory;

    protected $table = 'jenis_prestasi'; // Nama tabel tunggal
    protected $fillable = ['nama_jenis'];

    // Relasi ke tabel Prestasi (Nanti jika sudah ada)
    // public function prestasi()
    // {
    //     return $this->hasMany(Prestasi::class, 'jenis_id');
    // }
}
