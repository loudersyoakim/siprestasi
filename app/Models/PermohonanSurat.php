<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanSurat extends Model
{
    protected $table = 'permohonan_surats';
    protected $guarded = ['id'];

    protected $casts = [
        'data_isian' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function templateSurat()
    {
        return $this->belongsTo(TemplateSurat::class);
    }
}
