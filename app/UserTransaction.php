<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'status_id',
        'scheduled_time',
        'to_user_id',
    ];

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'id', 'status_id');
    }
}
