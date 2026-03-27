<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    protected $guarded = ['id'];

    public function jurusans()
    {
        return $this->hasMany(Jurusan::class);
    }
}
