<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserTransfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * Метод добавления новой запланированной транзакции
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserTransfer $userTransfer
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, UserTransfer $userTransfer, User $user)
    {
        $amount = $request->input('amount');
        $receiverId = $request->input('receiverId');
        $senderId = $request->input('senderId');
        $dateTime = $request->input('dateTime');

        //todo think about this validate
        if (($amount * 100) % 50 != 0) {
            abort(400, 'Сумма перевода не кратна 50 копейкам');
        }

        $this->validateTransactionFormFields($request);

        $balance = $user->find($senderId)->balance;

        //Сумма списаний
        $amountOfWriteOffs = $userTransfer->where([['status_id', 3], ['sender_id', $senderId]])->sum('amount');

        $userTempBalance = $balance - $amountOfWriteOffs;

        if ($userTempBalance < $amount) {
            abort(400, 'Недостаточно средств');
        }

        $userTransfer->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'amount' => $amount,
            'scheduled_time' => $dateTime,
            'status_id' => 3
        ]);
        return response('Транзакция запланирована', 200);
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
            'date_format' => 'Неккоректная дата',
            'exists' => 'Пользователя не существует',
            //todo delete middleware
            'different' => 'Нельзя сдалть перевод самому себе',
            'after' => 'Нельзя осуществить перевод задним числом'
        ];

        $this->validate($request, [
            'amount' => 'required|numeric|min:0.5|max:1000000',
            'receiverId' => 'required|integer|different:senderId|exists:users,id',
            'senderId' => 'required|integer|exists:users,id',
            'dateTime' => 'required|date_format:"Y-m-d H:00:00"|after:now',
        ], $messages);
    }

    /**
     * Метод удаления транзакции
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userTransfer = UserTransfer::find($id);

        if (!$userTransfer) {
            abort(400, 'Транзакция не найдена в базе данных');
        }

        $userTransfer->delete();
        return response('Успешно удалена', 200);
    }
}
