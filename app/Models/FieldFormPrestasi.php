<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class FieldFormPrestasi extends Model
{
    use SoftDeletes;
    // Tambahkan huruf 's' di belakangnya
    protected $table = 'field_form_prestasis';
    protected $guarded = [];

    protected $casts = [
        'opsi' => 'array',
        'is_required' => 'boolean',
    ];
}
