<?php

namespace Tests\Unit;

use App\Services\TransactionService;
use App\User;
use App\UserTransfer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MainTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    private $transactionService;
    private $userTransfer;
    private $user;

    protected function setUp()
    {
        $this->user = new User();
        $this->userTransfer = new UserTransfer();
        $this->transactionService = new TransactionService();

        parent::setUp();
    }

    /**
     * Тест отправляет POST запрос на создание запланированной транзакции
     * и проверяет в бд, добавилась ли она
     */
    public function testCreateTransfers()
    {
        $dateTime = Carbon::now()->addHour();
        $dateTime = $dateTime->format('Y-m-d H:00:00');
        $this->json('POST', 'transfers', [
            'senderId' => 1,
            'receiverId' => 2,
            'amount' => 1,
            'dateTime' => $dateTime
        ]);
        $this->assertDatabaseHas('users_transfers', [
            'sender_id' => 1,
            'receiver_id' => 2,
            'amount' => 1,
            'scheduled_time' => $dateTime
        ]);
    }

    /**
     * Тест отправляет POST запрос на создание запланированной транзакции
     * с невалидным полем баланс, превышающий текущий баланс пользователя и
     * проверяет что денег у пользователя недостаточно
     */
    public function testNotEnoughMoney()
    {
        $dateTime = Carbon::now()->addHour();
        $dateTime = $dateTime->format('Y-m-d H:00:00');
        $response = $this->json('POST', 'transfers', [
            'senderId' => 1,
            'receiverId' => 2,
            'amount' => 9999999,
            'dateTime' => $dateTime
        ]);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Тест проверяет, то что нельзя осуществить перевод самому себе
     */
    public function testTheSameUsers()
    {
        $dateTime = Carbon::now()->addHour();
        $dateTime = $dateTime->format('Y-m-d H:00:00');
        $response = $this->json('POST', 'transfers', [
            'senderId' => 1,
            'receiverId' => 1,
            'amount' => 100,
            'dateTime' => $dateTime
        ]);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Тест проверяет, что нельзя отправить дату без времени
     */
    public function testInvalidDate()
    {
        $dateTime = Carbon::now()->addHour();
        $invalidDate = $dateTime->format('Y-m-d');
        $response = $this->json('POST', 'transfers', [
            'senderId' => 1,
            'receiverId' => 2,
            'amount' => 100,
            'dateTime' => $invalidDate
        ]);
        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * Тест проверяет, что нельзя оформить перевод в прошедшем времени
     */
    public function testInvalidPastDate()
    {
        $dateTime = Carbon::now()->subHour();
        $invalidPastDateTime = $dateTime->format('Y-m-d H:00:00');
        $response = $this->json('POST', 'transfers', [
            'senderId' => 1,
            'receiverId' => 2,
            'amount' => 100,
            'dateTime' => $invalidPastDateTime
        ]);
        $this->assertEquals(422, $response->getStatusCode());
    }
}
