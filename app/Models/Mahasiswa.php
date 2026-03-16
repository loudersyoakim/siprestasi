<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $fillable = ['user_id', 'foto_profil', 'jenis_kelamin', 'angkatan', 'prodi_id', 'jurusan_id', 'fakultas_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}
