<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * App\Models\SmsChannel.
 *
 * @property-read \App\Models\Channel|null $channel
 *
 * @method static \Database\Factories\SmsChannelFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SmsChannel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsChannel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SmsChannel query()
 * @mixin \Eloquent
 */
class SmsChannel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function channel(): MorphOne
    {
        return $this->morphOne(Channel::class, 'channelable');
    }
}
