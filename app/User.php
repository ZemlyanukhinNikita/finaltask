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
        'first_name', 'last_name', 'balance',
    ];

    public function ownerTransactions()
    {
        return $this->hasMany(UserTransaction::class, 'from_user_id', 'id');
    }

    public function toUsersTransactions()
    {
        return $this->hasMany(UserTransaction::class, 'to_user_id', 'id');
    }
}
