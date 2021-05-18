<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'project_id',
        'task_id',
        'employee',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'notes',
        'client_view',
        'created_by',
    ];

    public function users()
    {
        return $this->hasOne('App\User', 'id', 'employee');
    }

    public function tasks()
    {
        return $this->hasOne('App\ProjectTask', 'id', 'task_id');
    }

    public function projects()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
}
