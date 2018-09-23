<?php

namespace App\Http\Controllers;

use App\UserTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param UserTransaction $userTransaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, UserTransaction $userTransaction)
    {
        $amount = $request->input('amount');
        $toUserId = $request->input('toUser');
        $fromUserId = $request->input('from_user_id');
        $dateTime = $request->input('dateTime');

        $userTransaction->create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'amount' => $amount,
            'scheduled_time' => $dateTime,
            'status_id' => 3
        ]);
        $message = 'Транзакция успешно запланирована на '. $dateTime . '!';
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
