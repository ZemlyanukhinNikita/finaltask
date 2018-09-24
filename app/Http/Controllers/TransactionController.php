<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Метод добавления новой запланированной транзакции
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserTransaction $userTransaction
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, UserTransaction $userTransaction, User $user)
    {
        $amount = $request->input('amount');
        $toUserId = $request->input('receiverId');
        $fromUserId = $request->input('senderId');
        $dateTime = $request->input('dateTime');

        if (($amount * 100) % 50 != 0) {
            return redirect()->back()->with('danger', 'Сумма не кратна 50 копейкам');
        }

        $this->validateTransactionFormFields($request);

        $balance = $user->find($fromUserId)->balance;

        //Сумма списаний
        $amountOfWriteOffs = $userTransaction->select(DB::raw('SUM(amount) as amountOfWriteOffs'))
            ->where([['status_id', 3], ['from_user_id', $fromUserId]])->first()->amountOfWriteOffs;

        $userTempBalance = $balance - $amountOfWriteOffs;

        if ($userTempBalance < $amount) {
            return redirect()->back()->with('danger', 'Недостаточно средств, ваш остаток ' . $userTempBalance . '₽');
        }

        $userTransaction->create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'amount' => $amount,
            'scheduled_time' => $dateTime,
            'status_id' => 3
        ]);

        $message = 'Транзакция успешно запланирована на ' . $dateTime . '!';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Метод валидаци полей формы
     * @param Request $request
     */
    private function validateTransactionFormFields(Request $request)
    {
        $messages = [
            'required' => 'Заполните обязательное поле :attribute',
            'integer' => 'Число должно быть целым :attribute.',
            'max' => 'Нельзя перевести больше 1000000.',
            'date_format' => 'Неккоректная дата'
        ];

        $this->validate($request, [
            'amount' => 'required|numeric|min:0.5|max:1000000',
            'receiverId' => 'required|integer',
            'senderId' => 'required|integer',
            'dateTime' => 'required|date_format:"Y-m-d H:00:00"',
        ], $messages);
    }

    /**
     * Метод удаления транзакции
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $userTransaction = UserTransaction::find($id);
        $userTransaction->delete();
        return redirect()->back()->with('success', 'Транзакция отменена');
    }

}
