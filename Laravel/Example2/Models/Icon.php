<?php

namespace App;

use Buckii\Larakit\Models\Model;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\Form;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class Icon extends Model
{
    protected $table = 'icons';

    protected $fillable = [
        'pack_id',
        'id',
        'tags',
        'png_size_1_link',
        'aws_icon_stored_name',
        'aws_icon_display_name'
    ];

    public static function getStorage()
    {
        return Storage::disk('s3');
    }

    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

}