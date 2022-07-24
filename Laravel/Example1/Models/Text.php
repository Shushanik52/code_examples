<?php

namespace App;

use Buckii\Larakit\Models\Model;
use Ramsey\Uuid\Uuid;

class TextSnippet extends Model
{
    public $incrementing = false;

    protected $table = 'texts';

    protected $keyType = 'string';

    protected $fillable = [
        'debug_exported_json',
        'text',
    ];

    protected $casts = [
        'debug_exported_json' => 'array',
        'display_info' => 'array',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }
}