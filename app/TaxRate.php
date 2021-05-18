<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'created_by',
    ];
}
