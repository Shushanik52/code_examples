<?php


namespace App;

use Buckii\Larakit\Models\Model;
use Illuminate\Http\Request;

class Image extends Model
{
    protected $table = 'images';

    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'html_link_url',
        'views',
        'graphics_data',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('image.archived_at')->whereNull('image.isTemplate');
    }

    public function scopeAsTemplate($query)
    {
        return $query->whereNotNull('image.isTemplate');
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function text()
    {
        return $this->hasMany(Text::class);
    }


}