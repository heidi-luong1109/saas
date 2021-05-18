<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'department',
        'designation',
        'title',
        'date',
        'time',
        'notes',
        'created_by',
    ];

    public function departments()
    {
        return $this->hasOne('App\Department', 'id', 'department');
    }

    public function designations()
    {
        return $this->hasOne('App\Designation', 'id', 'designation');
    }
}
