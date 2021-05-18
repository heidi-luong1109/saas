<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
}
