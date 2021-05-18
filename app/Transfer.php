<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'employee_id',
        'department_id',
        'transfer_date',
        'description',
        'created_by',
    ];

    public function department()
    {
        return $this->hasOne('App\Department', 'id', 'department_id');
    }

    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }
}
