<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'purpose_of_visit',
        'place_of_visit',
        'description',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }
}
