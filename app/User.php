<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'balance',
    ];

    public function ownerTransactions()
    {
        return $this->hasMany(UserTransfer::class, 'sender_id', 'id');
    }

    public function toUsersTransactions()
    {
        return $this->hasMany(UserTransfer::class, 'receiver_id', 'id');
    }
}
