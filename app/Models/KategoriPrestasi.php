<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPrestasi extends Model
{
    use HasFactory;

    protected $table = 'kategori_prestasi';
    protected $fillable = ['nama_kategori'];

    // public function prestasi()
    // {
    //     return $this->hasMany(Prestasi::class, 'kategori_id');
    // }
}
