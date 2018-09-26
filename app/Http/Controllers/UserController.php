<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTransfer;
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
        $users = DB::table('users as senders')
            ->leftJoin('users_transfers', function ($join) {
                $join->on('senders.id', '=', 'users_transfers.sender_id')
                    ->whereRaw('users_transfers.id = (select us.id from users_transfers us
                where us.sender_id = senders.id order by us.id desc limit 1)');
            })->leftJoin('users as receivers', function ($join) {
                $join->on('receivers.id', '=', 'users_transfers.receiver_id');
            })->select(
                'senders.id as id',
                'senders.first_name as senderFirstName',
                'senders.last_name as senderLastName',
                'receivers.first_name as receiverFirstName',
                'receivers.last_name as receiverLastName',
                'users_transfers.amount',
                'users_transfers.scheduled_time',
                'users_transfers.status_id'
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
        $userTransfers = UserTransfer::where('sender_id', $user->id)->get();

        return view('user', [
            'user' => $user,
            'other_users' => $otherUsers,
            'transfers' => $userTransfers
        ]);
    }
}
