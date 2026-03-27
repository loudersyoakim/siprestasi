<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konten extends Model
{
    protected $table = 'konten';
    protected $guarded = ['id'];

    public function penulis()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
