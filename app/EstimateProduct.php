<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstimateProduct extends Model
{
    protected $fillable = [
        'item',
        'estimate',
        'quantity',
        'tax',
        'discount',
        'total',
    ];

    public function items()
    {
        return $this->hasOne('App\Item', 'id', 'item');
    }
}
