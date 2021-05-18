<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'name',
        'file',
        'description',
        'created_by',
    ];
}
