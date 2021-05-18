<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadCall extends Model
{
    protected $fillable = [
        'lead_id',
        'subject',
        'call_type',
        'duration',
        'user_id',
        'description',
        'call_result',
    ];

    public function getLeadCallUser()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
