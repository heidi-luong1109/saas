<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',
        'leave_type',
        'applied_on',
        'start_date',
        'end_date',
        'total_leave_days',
        'leave_reason',
        'remark',
        'status',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'employee_id');
    }

    public function leaveType()
    {
        return $this->hasOne('App\LeaveType', 'id', 'leave_type');
    }
}
