<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'name',
        'department',
        'created_by',
    ];

    public function departments()
    {
        return $this->hasOne('App\Department', 'id', 'department');
    }
}
