<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateSurat extends Model
{
    use SoftDeletes;

    protected $table = 'template_surats';
    protected $guarded = ['id'];

    public function fields()
    {
        return $this->hasMany(FieldTemplateSurat::class)->orderBy('urutan', 'asc');
    }

    public function permohonan()
    {
        return $this->hasMany(PermohonanSurat::class);
    }
}
