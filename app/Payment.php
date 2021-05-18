<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'client',
        'payment_method',
        'reference',
        'description',
        'created_by',
    ];

    public function clients()
    {
        return $this->hasOne('App\User', 'id', 'client');
    }

    public function paymentMethods()
    {
        return $this->hasOne('App\PaymentMethod', 'id', 'payment_method');
    }
}
