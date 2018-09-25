<?php

namespace App\Services;


use App\User;
use App\UserTransfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    /**
     * Метод выполняет запланированные транзакции
     * @param UserTransfer $userTransfer
     * @param User $user
     */
    public function beginTransactions(UserTransfer $userTransfer, User $user)
    {
        //todo think about timezones
        $userTransfers = $userTransfer->where([['status_id', 3], ['scheduled_time', '<=', Carbon::now()->addHours(7)]])->get();

        foreach ($userTransfers as $transfer) {
            DB::beginTransaction();
            try {
                $newBalanceFrom = $transfer->sender->balance - $transfer->amount;
                $newBalanceTo = $transfer->receiver->balance + $transfer->amount;

                $user->find($transfer->sender_id)->update(['balance' => $newBalanceFrom]);
                $user->find($transfer->receiver_id)->update(['balance' => $newBalanceTo]);

                $transfer->update(['status_id' => 1]);
                DB::commit();
                Log::info('Перевод с id = ' . $transfer->id . ' успешно выполнен');
            } catch (\Exception $e) {
                DB::rollback();
                Log::warning('Перевод с id = ' . $transfer->id . ' не был выполнен');
                $transfer->update(['status_id' => 2]);
            }
        }
    }
}
