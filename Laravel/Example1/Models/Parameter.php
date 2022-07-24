<?php

namespace App;

use Buckii\Larakit\Models\Model;


class Parameter extends Model
{
    protected $table = 'parameters';

    public $incrementing = false;

    protected $fillable = [
        'param_name',
        'param_default_value',
        'merge_tag_name',
        'merge_tag_value',
    ];


    public function text()
    {
        return $this->belongsTo(Text::class);
    }
}