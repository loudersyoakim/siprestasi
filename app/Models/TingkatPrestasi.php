<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatPrestasi extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu Tingkat bisa dimiliki banyak Prestasi
    public function prestasis()
    {
        return $this->hasMany(Prestasi::class, 'tingkat_prestasi_id');
    }
}
