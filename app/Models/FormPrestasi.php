<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPrestasi extends Model
{
    use SoftDeletes; // Pakai soft delete agar data lama aman

    protected $table = 'form_prestasis'; // Pastikan nama tabel benar
    protected $guarded = ['id'];

    public function fields()
    {
        return $this->hasMany(FieldFormPrestasi::class)->orderBy('urutan', 'asc');
    }

    public function prestasis()
    {
        return $this->hasMany(Prestasi::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
