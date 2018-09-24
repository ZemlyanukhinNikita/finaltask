<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'users_transactions';
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'status_id',
        'scheduled_time',
        'to_user_id',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
