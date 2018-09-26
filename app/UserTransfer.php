<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserTransfer extends Model
{
    protected $table = 'users_transfers';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'status_id',
        'scheduled_time',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function getCountSuccessTransfers()
    {
        return $this->where([['status_id',1], ['scheduled_time', '>=' ,Carbon::now()->subDay()]])->count();
    }

    public function getAmountSumSuccessTransfers()
    {
        return $this->where([['status_id',1], ['scheduled_time', '>=' ,Carbon::now()->subDay()]])->sum('amount');
    }
}
