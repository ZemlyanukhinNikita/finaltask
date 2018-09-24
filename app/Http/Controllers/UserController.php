<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransaction;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Метод отображает всех пользователей, и информацию об их последней транзакции
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = DB::table('users as senders')->leftJoin('users_transactions', function ($join) {
            $join->on('senders.id', '=', 'users_transactions.from_user_id')
                ->whereRaw('users_transactions.id = (select us.id from users_transactions us
                where us.from_user_id = senders.id order by us.id desc limit 1)');
        })->leftJoin('users as receivers', function ($join) {
            $join->on('receivers.id', '=', 'users_transactions.to_user_id');
        })->select(
            'senders.id as id',
            'senders.first_name as senderFirstName',
            'senders.last_name as senderLastName',
            'receivers.first_name as receiverFirstName',
            'receivers.last_name as receiverLastName',
            'users_transactions.amount',
            'users_transactions.scheduled_time',
            'users_transactions.status_id'
        )->get();
        return view('users', ['users' => $users]);
    }

    /**
     * Отображаем текущего пользователя и пользователей которым можно совершить перевод
     * и все транзакции текущего пользователя
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::find($id);
        $otherUsers = User::where('id', '<>', $user->id)->get();
        $userTransactions = UserTransaction::where('from_user_id', $user->id)->get();
        return view('user', [
            'user' => $user,
            'other_users' => $otherUsers,
            'transactions' => $userTransactions
        ]);
    }
}
