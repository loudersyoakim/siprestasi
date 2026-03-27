<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldFormPrestasi extends Model
{
    protected $table = 'field_form_prestasis';
    protected $guarded = ['id'];

    // Convert JSON di database menjadi Array saat ditarik ke PHP
    protected $casts = [
        'opsi' => 'array',
        'aturan_validasi' => 'array',
        'is_required' => 'boolean',
    ];

    public function formPrestasi()
    {
        return $this->belongsTo(FormPrestasi::class);
    }
}
