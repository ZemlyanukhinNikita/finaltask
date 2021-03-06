<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        if (($amount * 100) % 50 != 0) {
            return redirect()->back()->with('danger', 'Сумма не кратна 50 копейкам');
        }

        $this->validateTransferFormFields($request);

        try {
            DB::beginTransaction();
            $balance = $user->find($senderId)->balance;
            //Сумма списаний
            $amountOfWriteOffs = $userTransfer->where([
                ['status_id', 3],
                ['sender_id', $senderId]
            ])->sum('amount');
            $userTempBalance = $balance - $amountOfWriteOffs;

            if ($userTempBalance < $amount) {
                DB::rollback();
                return redirect()->back()->with('danger',
                    'Недостаточно средств, ваш остаток ' . $userTempBalance . '₽');
            }

            $userTransfer->create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'amount' => $amount,
                'scheduled_time' => $dateTime,
                'status_id' => 3
            ]);
            DB::commit();

            $message = 'Транзакция успешно запланирована на ' . $dateTime . '!';
            Log::info($message);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Транзакция не запланирована, ошибка бд';
            Log::warning($message);

            return redirect()->back()->with('danger', $message);
        }
    }

    /**
     * Метод валидаци полей формы
     * @param Request $request
     */
    private function validateTransferFormFields(Request $request)
    {
        $messages = [
            'required' => 'Заполните обязательное поле :attribute',
            'integer' => ':attribute должно быть целым',
            'max' => 'Нельзя перевести больше 1000000',
            'date' => 'Некорректная дата',
            'exists' => 'Пользователя не существует',
            'different' => 'Нельзя осуществить перевод самому себе',
            'after' => 'Нельзя осуществить перевод в прошедшем времени'
        ];

        $this->validate($request, [
            'amount' => 'required|numeric|min:0.5|max:1000000',
            'receiverId' => 'required|integer|different:senderId|exists:users,id',
            'senderId' => 'required|integer|exists:users,id',
            'dateTime' => 'required|date|after:now',
        ], $messages);
    }

    /**
     * Метод удаления транзакции
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $userTransfer = UserTransfer::find($id);
        $userTransfer->delete();
        return redirect()->back()->with('success', 'Транзакция отменена');
    }
}
