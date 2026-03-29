<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    // Ini tetap aman, semua kolom baru otomatis 'fillable' kecuali ID
    protected $guarded = ['id'];

    protected $casts = [
        'data_dinamis' => 'array',
        'is_published' => 'boolean',
        'setting_statis' => 'array',
        'tahun_kegiatan' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke User (Ketua/Pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Formulir/Kategori Prestasi
    public function formPrestasi()
    {
        return $this->belongsTo(FormPrestasi::class);
    }

    // Relasi untuk menarik semua anggota tim di prestasi ini (Tabel Pivot)
    public function anggota()
    {
        return $this->belongsToMany(User::class, 'anggota_prestasis', 'prestasi_id', 'user_id')
            ->withPivot('peran')
            ->withTimestamps();
    }


    // Relasi ke Master Data Tingkat
    public function tingkatPrestasi()
    {
        return $this->belongsTo(TingkatPrestasi::class, 'tingkat_prestasi_id');
    }

    // Relasi ke Master Data Capaian
    public function capaianPrestasi()
    {
        return $this->belongsTo(CapaianPrestasi::class, 'capaian_prestasi_id');
    }
}
