<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\UserTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Метод возвращает респонс с json всех пользователей и их последней транзакцией
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users as senders')->leftJoin('users_transfers', function ($join) {
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

        if (!$users) {
            return response()->json([], 204);
        }
        return response()->json($users, 200);
    }

    /**
     * Метод возвращает респонс с jsonом текущего юзера, его транзакции, всех остальных пользователей
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $otherUsers = User::where('id', '<>', $user->id)->get();
        $userTransfers = UserTransfer::where('sender_id', $user->id)->get();

        if (!$user) {
            return response()->json([], 204);
        }

        return response()->json([
            'user' => $user,
            'other_users' => $otherUsers,
            'transfers' => $userTransfers
        ], 200);
    }
}
