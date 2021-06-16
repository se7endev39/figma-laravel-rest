<?php

namespace App\Services;

use Twilio\Rest\Client;

abstract class TwilioClient
{
    /**
     * @var Client
     */
    private $_Client;

    /**
     * TwilioClient constructor.
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct()
    {
        $sid = get_option('twilio_account_sid');
        $token = get_option('twilio_auth_token');

        $this->_Client = new Client($sid, $token);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->_Client;
    }
}
