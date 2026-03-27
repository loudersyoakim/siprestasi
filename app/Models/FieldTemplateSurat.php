<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldTemplateSurat extends Model
{
    protected $table = 'field_template_surats';
    protected $guarded = ['id'];

    protected $casts = [
        'opsi' => 'array',
        'is_required' => 'boolean',
    ];

    public function templateSurat()
    {
        return $this->belongsTo(TemplateSurat::class);
    }
}
