<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [
        'employee_id',
        'award_type',
        'date',
        'gift',
        'description',
        'created_by',
    ];

    public function awardType()
    {
        return $this->hasOne('App\AwardType', 'id', 'award_type');
    }

    public function employee()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }
}
