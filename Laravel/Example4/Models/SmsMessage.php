<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\SmsMessage.
 *
 * @property-read \App\Models\Message|null $message
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SmsMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsMessage query()
 * @mixin \Eloquent
 */
class SmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'sid',
    ];

    protected $casts = [
        'to' => 'json',
    ];

    public function message(): MorphOne
    {
        return $this->morphOne(Message::class, 'messageable');
    }
}
