<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'data_dinamis' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formPrestasi()
    {
        return $this->belongsTo(FormPrestasi::class);
    }

    // Relasi untuk menarik semua anggota tim di prestasi ini
    public function anggota()
    {
        return $this->belongsToMany(User::class, 'anggota_prestasis', 'prestasi_id', 'user_id')
            ->withPivot('peran')
            ->withTimestamps();
    }
}
