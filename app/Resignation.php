<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    protected $fillable = [
        'employee_id',
        'notice_date',
        'resignation_date',
        'description',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }
}
