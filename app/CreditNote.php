<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    protected $fillable = [
        'invoice',
        'client',
        'amount',
        'date',
    ];

    public function client()
    {
        return $this->hasOne('App\User', 'client', 'id');
    }
}
