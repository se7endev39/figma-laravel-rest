<?php

namespace App\Channels;

use App\Services;
use App\Notifications\SmsMessage;
use Illuminate\Notifications\Notification;

class TwilioSms
{
    /**
     * @param $notifiable
     * @param Notification $notification
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTwilioSms($notifiable);

        (new Services\TwilioSms())->sendSms(
            $message->getRecipient(), //Recipent Phone Number
            $message->getContent()
        );
    }
}