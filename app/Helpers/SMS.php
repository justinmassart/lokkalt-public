<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;
use Twilio\Rest\Client;

class SMS extends Facade
{
    protected $sid;

    protected $token;

    protected $number;

    protected $twilio;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->number = config('services.twilio.number');
        $this->twilio = new Client($this->sid, $this->token);
    }

    public static function send(string $to, $message)
    {
        $instance = self::getFacadeRoot();

        $instance->twilio->messages->create(
            $to,
            [
                'body' => $message,
                'from' => $instance->number,
            ]
        );
    }

    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
