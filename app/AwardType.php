<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
}
