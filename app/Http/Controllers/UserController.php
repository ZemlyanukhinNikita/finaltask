<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
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
            'users_transactions.scheduled_time'


        )->get();
        return view('users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $otherUsers = User::where('id', '<>', $user->id)->get();
        return view('user', [
            'user' => $user,
            'other_users' => $otherUsers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
