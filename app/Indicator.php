<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $fillable = [
        'designation',
        'customer_experience',
        'marketing',
        'administration',
        'professionalism',
        'integrity',
        'attendance',
        'created_by',
        'created_user',
    ];

    public static $technical = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
        'Expert / Leader',
    ];

    public static $organizational = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
    ];


    public function departments()
    {
        return $this->hasOne('App\Department', 'id', 'department');
    }

    public function designations()
    {
        return $this->hasOne('App\Designation', 'id', 'designation');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_user');
    }
}
