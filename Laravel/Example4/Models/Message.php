<?php

namespace App\Models;

use App\Events\Models\Message\Created;
use App\Events\Models\Message\Saved;
use App\Events\Models\Message\Updated;
use App\Traits\Filterable;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class Message extends Model
{
    use HasFactory, Filterable, PivotEventTrait;

    public const TYPE_INBOUND = 'inbound';
    public const STATUS_RECEIVED = 'received';
    public const STATUS_ON_QUEUE = 'on_queue';


    protected $fillable = [
        'type', 'metadata', 'messageable_type', 'messageable_id', 'channel_id', 'staff_id',
        'message', 'to', 'project_id', 'is_conversation', 'status', 'failed_message',
        'from', 'ignore_consent', 'reply_to',
    ];

    protected $appends = [
        'from_system',
        'group',
        'sender',
    ];

    protected $casts = [
        'metadata' => 'json',
        'to' => 'json',
        'is_conversation' => 'boolean',
        'ignore_consent' => 'boolean',
    ];

    /** @var array The event map for the model */
    protected $dispatchesEvents = [
        'created' => Created::class,
        'updated' => Updated::class,
        'saved' => Saved::class,
    ];

    /** {@inheritdoc} */
    protected static function booted()
    {
        static::creating(function (self $message): void {
            $message->is_conversation = $message->isConversation();
            $message->status ??= ($message->type === self::TYPE_INBOUND ? self::STATUS_RECEIVED : self::STATUS_ON_QUEUE);
        });
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class);
    }

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    private function isConversation(): bool
    {
        return $this->messageable instanceof SmsMessage;
    }
}