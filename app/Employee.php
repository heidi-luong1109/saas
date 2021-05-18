<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'designation',
        'joining_date',
        'exit_date',
        'gender',
        'address',
        'mobile',
        'salary_type',
        'salary',
        'created_by',
    ];

    public static $statues = [
        'Inactive',
        'Active',
    ];

    public function departments()
    {
        return $this->hasOne('App\Department', 'id', 'department');
    }

    public function designations()
    {
        return $this->hasOne('App\Designation', 'id', 'designation');
    }

    public function salaryType()
    {
        return $this->hasOne('App\SalaryType', 'id', 'salary_type');
    }

    public function users()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function present_status($employee_id, $data)
    {
        return Attendance::where('employee_id', $employee_id)->where('date', $data)->first();
    }
}
