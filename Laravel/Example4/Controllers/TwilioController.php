<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Sms\StoreTwilioSmsAttachmentJob;
use App\Models\Message;
use App\Models\SmsChannel;
use App\Models\SmsMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Twilio;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function handleSMS(Request $request): Response
    {
        abort_if($request->input('SmsStatus') !== 'received', Response::HTTP_NO_CONTENT);

        $smsChannel = SmsChannel::where('phone_number', $request->input('To'))->first();

        abort_unless($smsChannel instanceof SmsChannel, Response::HTTP_NO_CONTENT);

        abort_if(SmsMessage::where('sid', $request->input('SmsSid'))->first() instanceof SmsMessage, Response::HTTP_NO_CONTENT);

        Message::create([
            'project_id' => $smsChannel->channel->project->id,
            'from' => $request->input('From'),
            'to' => [['phone' => $request->input('To')]],
            'type' => Message::TYPE_INBOUND,
            'message' => $request->input('Body'),
            'channel_id' => $smsChannel->channel->id,
            'status' => Message::STATUS_RECEIVED,
            'messageable_type' => SmsMessage::class,
            'messageable_id' => SmsMessage::create([
                'sid' => $request->input('SmsSid'),
            ])->id,
        ]);

        StoreTwilioSmsAttachmentJob::dispatchIf($request->has('MediaUrl0'), $request->all()); //store sent files in our db

        return response()->noContent();
    }

}
