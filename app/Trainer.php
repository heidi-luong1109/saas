<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'contact',
        'email',
        'address',
        'expertise',
        'created_by',
    ];
    
}
