<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;
use Zelenin\SmsRu\Exception\Exception;

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
        try {
            $this->client->smsSend($sms);
            Log::info('СМС сообщение успешно отправлено');
        } catch (Exception $e) {
            Log::error('Произошла ошибка при отправке СМС сообщения');
        }
    }
}
