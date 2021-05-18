<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'employee_id',
        'designation_id',
        'promotion_title',
        'promotion_date',
        'description',
        'created_by',
    ];

    public function designation()
    {
        return $this->hasOne('App\Designation', 'id', 'designation_id');
    }

    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }
}
