<?php

namespace Tests\Unit;

use App\Services\TransactionService;
use App\User;
use App\UserTransfer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
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
     * Тест выполняет запланированную транзакцию, и проверяет
     * начилслился ли баланс получателю и снялся ли у отправителя
     */
    public function testExecuteTransaction()
    {
        $dateTime = Carbon::now()->subHour();
        $dateTime = $dateTime->format('Y-m-d H:00:00');

        $this->userTransfer->create([
            'sender_id' => 1,
            'receiver_id' => 2,
            'amount' => 100,
            'status_id' => 3,
            'scheduled_time' => $dateTime,
        ]);

        $userSender = $this->user->find(1);
        $userReceiver = $this->user->find(2);

        $this->transactionService->beginTransactions($this->userTransfer, $this->user);

        $userSenderAfterTransaction = $this->user->find(1);
        $userReceiverAfterTransaction = $this->user->find(2);

        $this->assertEquals($userSender->balance - 100, $userSenderAfterTransaction->balance);
        $this->assertEquals($userReceiver->balance + 100, $userReceiverAfterTransaction->balance);
    }
}
