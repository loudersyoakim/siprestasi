<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';
    protected $fillable = ['jurusan_id', 'nama_prodi', 'jenjang', 'kode_prodi'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function fakultas()
    {
        return $this->hasOneThrough(
            Fakultas::class,
            Jurusan::class,
            'id',
            'id',
            'jurusan_id',
            'fakultas_id'
        );
    }
}
