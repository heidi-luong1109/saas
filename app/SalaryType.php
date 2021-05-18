<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
}
