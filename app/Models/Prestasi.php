<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';

    protected $fillable = [
        'nama_prestasi',
        'tingkat_id',
        'jenis_id',
        'kategori_id',
        'tahun_akademik_id',
        'sertifikat',
        'tanggal_peroleh',
        'deskripsi',
        'status',
        'alasan_ditolak',
        'is_published'
    ];

    // Relasi Many-to-Many ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'prestasi_user', 'prestasi_id', 'user_id')->withTimestamps();
    }

    // Relasi lainnya (Tingkat, Jenis, Kategori, Tahun Akademik)
    public function tingkat()
    {
        return $this->belongsTo(TingkatPrestasi::class);
    }
    public function jenis()
    {
        return $this->belongsTo(JenisPrestasi::class);
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriPrestasi::class);
    }
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }
}
