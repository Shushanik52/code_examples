<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pack extends Model
{
    protected $table = 'packs';
    protected $fillable = [
        'id',
        'pack_name',
        'pack_style_name',
        'pack_items',
        'tags',
        'cover_image',
        'isFavorite',
    ];

    public static function getStorage()
    {
        return Storage::disk('s3');
    }

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }
}