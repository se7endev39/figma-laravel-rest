<?php

namespace App\Services;

class TwilioSms extends TwilioClient
{
    /**
     * @param string $phoneNumber
     * @param string $content
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendSms(string $phoneNumber, string $content)
    {
        $client = $this->getClient();

        $client->messages->create(
            $phoneNumber,
            [
                'from' => get_option('twilio_number'),
                'body' => $content
            ]
        );
    }
}
