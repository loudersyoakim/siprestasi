<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class KategoriPrestasi extends Model
{
    use SoftDeletes;

    // Tambahkan huruf 's' di belakangnya
    protected $table = 'kategori_prestasis';
    protected $guarded = [];

    public function fields()
    {
        return $this->hasMany(FieldFormPrestasi::class, 'kategori_prestasi_id')->orderBy('urutan');
    }
}
