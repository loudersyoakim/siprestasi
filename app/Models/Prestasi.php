<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasis';
    protected $guarded = [];

    // Cast kolom JSON otomatis menjadi Array
    protected $casts = [
        'data_dinamis' => 'array',
    ];

    // Relasi ke Mahasiswa (User) yang melaporkan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kategori Form Prestasi
    public function kategori()
    {
        return $this->belongsTo(KategoriPrestasi::class, 'kategori_prestasi_id');
    }
}
