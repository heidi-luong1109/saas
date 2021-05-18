<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLead extends Model
{
    protected $fillable = [
        'user_id',
        'lead_id',
    ];

    public function getLeadUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
