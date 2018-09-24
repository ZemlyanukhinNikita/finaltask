<?php

namespace App\Services;


use App\User;
use App\UserTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    /**
     * Метод выполняет запланированные транзакции
     * @param UserTransaction $userTransaction
     * @param User $user
     */
    public function beginTransactions(UserTransaction $userTransaction, User $user)
    {
        //todo think about timezones
        $userTransactions = $userTransaction->where([['status_id', 3], ['scheduled_time', '<=', Carbon::now()->addHours(7)]])->get();

        foreach ($userTransactions as $transaction) {
            DB::beginTransaction();
            try {
                $newBalanceFrom = $transaction->sender->balance - $transaction->amount;
                $newBalanceTo = $transaction->receiver->balance + $transaction->amount;

                $user->find($transaction->from_user_id)->update(['balance' => $newBalanceFrom]);
                $user->find($transaction->to_user_id)->update(['balance' => $newBalanceTo]);

                $transaction->update(['status_id' => 1]);
                DB::commit();
                Log::info('Транзаккция с id = ' . $transaction->id . 'успешно выполнена');
            } catch (\Exception $e) {
                DB::rollback();
                Log::warning('Транзаккция с id = ' . $transaction->id . 'не была выполнена');
                $transaction->update(['status_id' => 2]);
            }
        }
    }
}
