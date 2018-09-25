<?php

namespace Tests\Unit;

use App\Services\TransactionService;
use App\User;
use App\UserTransfer;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    private $sender;
    private $receiver;
    private $transactionService;
    private $userTransfer;
    private $user;
    private $request;
    private $client;
    private $dateTime;

    protected function setUp()
    {
        $this->client = new Client();
        $this->user = new User();
        $this->userTransfer = new UserTransfer();

        $this->dateTime = Carbon::now()->addHours(1);
        $this->dateTime = $this->dateTime->format('Y-m-d H:00:00');

        $this->transactionService = new TransactionService();
//        $this->sender = [
//            'id' => 999,
//            'first_name' => 'Тест',
//            'last_name' => 'Тестовый',
//            'balance' => 10000
//        ];
//
//        $this->receiver = [
//            'id' => 1000,
//            'first_name' => 'Тест',
//            'last_name' => 'Тестовый',
//            'balance' => 0
//        ];
        parent::setUp();
    }

    public function testCreateTransactions()
    {

        $this->client->request('POST', 'http://localhost:8080/transfers', [
            //todo send csrf token
//                'headers'  => [
//                    'X-CSRF-TOKEN' => 'eyJpdiI6IjBoNWZzVGFHRHE1NHYyWVhcLzB6M0FRPT0iLCJ2YWx1ZSI6IkdTSk5adUhxcWNleHc0UzNCYWFOaGNkRW9KZ1VSOW9ldk1NWWpcL3d3S3JvZUxWR0QyTENieXhacjFsdWJ5aXhwIiwibWFjIjoiZjZkNzYzZjAxZTIwODAzNTAzZmM3N2Y3MWY1ZTQ4YjNhMDFmNThmMmI1MDYzMjY2NTczMGExZWM5NDg0YTU3OCJ9'
//                ],
            'form_params' => [
                'senderId' => 1,
                'receiverId' => 2,
                'amount' => 100,
                'dateTime' => $this->dateTime
            ]
        ]);

        $this->assertDatabaseHas('users_transfers', ['sender_id' => 1, 'receiver_id' => 2, 'amount' => 100, 'scheduled_time' => $this->dateTime]);

        $this->userTransfer->where([['sender_id', 1], ['receiver_id', 2], ['amount', 100], ['scheduled_time', $this->dateTime]])->delete();
        $this->assertDatabaseMissing('users_transfers', ['sender_id' => 1, 'receiver_id' => 2, 'amount' => 100, 'scheduled_time' => $this->dateTime]);
    }

    public function testCheckTransaction()
    {

        $this->client->request('POST', 'http://localhost:8080/transfers', [
            //todo send csrf token
//                'headers'  => [
//                    'X-CSRF-TOKEN' => 'eyJpdiI6IjBoNWZzVGFHRHE1NHYyWVhcLzB6M0FRPT0iLCJ2YWx1ZSI6IkdTSk5adUhxcWNleHc0UzNCYWFOaGNkRW9KZ1VSOW9ldk1NWWpcL3d3S3JvZUxWR0QyTENieXhacjFsdWJ5aXhwIiwibWFjIjoiZjZkNzYzZjAxZTIwODAzNTAzZmM3N2Y3MWY1ZTQ4YjNhMDFmNThmMmI1MDYzMjY2NTczMGExZWM5NDg0YTU3OCJ9'
//                ],
            'form_params' => [
                'senderId' => 1,
                'receiverId' => 2,
                'amount' => 100,
                'dateTime' => $this->dateTime
            ]
        ]);

        $userSender = $this->user->find(1);
        $userReceiver = $this->user->find(2);

        $this->transactionService->beginTransactions($this->userTransfer, $this->user);

        $userSenderAfterTransaction = $this->user->find(1);
        $userReceiverAfterTransaction = $this->user->find(2);

        $this->assertEquals($userSender->balance - 100, $userSenderAfterTransaction->balance);
        $this->assertEquals($userReceiver->balance + 100, $userReceiverAfterTransaction->balance);

        $userSenderAfterTransaction->balance += 100;
        $userReceiverAfterTransaction->balance -= 100;

        $userSenderAfterTransaction->save();
        $userReceiverAfterTransaction->save();
    }
}
