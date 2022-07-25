<?php

namespace App\Jobs\Sms;


use App\Models\Message;
use App\Models\SmsMessage;
use App\Traits\Jobs\ReportOnFailure;
use App\Traits\Jobs\RetryWithBackoff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Storage;


class StoreTwilioSmsAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RetryWithBackoff, ReportOnFailure;

    public function __construct(private array $smsMediaData)
    {
    }

    public function handle(): void
    {
        $smsMessage = SmsMessage::where('sid', $this->smsMediaData['SmsSid'])->first();

        if (!$smsMessage instanceof SmsMessage) {
            $this->fail(new RuntimeException(sprintf("Unable to find email message for message id %s", $this->smsMediaData['SmsSid'])));
            return;
        }

        $mediaHeader = get_headers( $this->smsMediaData['MediaUrl0'], true);

        $fullPath = vsprintf('%s/%s/%s/%s', [
            $smsMessage->message->project->id,
            Message::class,
            $smsMessage->id,
            basename($this->smsMediaData['MediaUrl0']),
        ]);

        $ch = curl_init($this->smsMediaData['MediaUrl0']);
        Storage::disk('gcs')->put($fullPath, curl_exec($ch)); //store media in gcs disk
        curl_close($ch);

        $smsMessage->message->files()->create([
            'name' => basename($this->smsMediaData['MediaUrl0']),
            'original_name' => basename($this->smsMediaData['MediaUrl0']),
            'mime_type' =>  $mediaHeader["Content-Type"][1],
            'size' =>  $mediaHeader["Content-Length"][1],
            'disk' => 'gcs',
            'path' => $fullPath,
        ]);

    }

}
