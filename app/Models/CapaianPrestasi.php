<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianPrestasi extends Model
{
    protected $guarded = ['id'];

    // Relasi: Satu Capaian bisa dimiliki banyak Prestasi
    public function prestasis()
    {
        return $this->hasMany(Prestasi::class, 'capaian_prestasi_id');
    }
}
