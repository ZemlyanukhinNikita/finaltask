<?php

namespace App\Services;

use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;

class SmsService
{
    private $client;

    public function __construct()
    {
        $this->client = new Api(new ApiIdAuth('1B2E6DCE-73D7-8A5C-D610-5463C01E68D3'));
    }

    public function sendSms($phone, $text)
    {
        $sms = new Sms($phone, $text);
        $this->client->smsSend($sms);
    }
}
