<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // WAJIB TAMBAH INI

class Konten extends Model
{
    // Tambahkan prestasi_id agar bisa disimpan & dicari
    protected $fillable = [
        'user_id',
        'prestasi_id',
        'title',
        'slug',
        'category',
        'content',
        'thumbnail',
        'is_published'
    ];

    // Relasi ke Prestasi (Opsional tapi bagus buat ke depannya)
    public function prestasi()
    {
        return $this->belongsTo(Prestasi::class, 'prestasi_id');
    }

    // Mutator untuk Slug Otomatis
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        // Gunakan \Illuminate\Support\Str jika tidak di-import di atas
        $this->attributes['slug'] = Str::slug($value) . '-' . time();
    }
}
