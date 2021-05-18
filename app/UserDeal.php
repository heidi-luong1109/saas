<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDeal extends Model
{
    protected $fillable = [
        'user_id',
        'deal_id',
    ];

    public function getDealUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
