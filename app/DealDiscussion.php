<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealDiscussion extends Model
{
    protected $fillable = [
        'deal_id',
        'comment',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
