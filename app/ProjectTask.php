<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'description',
        'due_date',
        'start_date',
        'assign_to',
        'project_id',
        'milestone_id',
        'status',
        'order',
        'stage',
    ];

    public function taskUser()
    {
        return $this->hasOne('App\User', 'id', 'assign_to');
    }

    public function comments()
    {
        return $this->hasMany('App\ProjectTaskComment', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskFiles()
    {
        return $this->hasMany('App\ProjectTaskFile', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskCheckList()
    {
        return $this->hasMany('App\ProjectTaskCheckList', 'task_id', 'id')->orderBy('id', 'DESC');
    }

    public function taskCompleteCheckListCount()
    {
        return $this->hasMany('App\ProjectTaskCheckList', 'task_id', 'id')->where('status', '=', '1')->count();
    }

    public function taskTotalCheckListCount()
    {
        return $this->hasMany('App\ProjectTaskCheckList', 'task_id', 'id')->count();
    }

    public function milestone()
    {
        return $this->hasOne('App\Milestone', 'id', 'milestone_id');
    }

    public function stages()
    {
        return $this->hasOne('App\ProjectStage', 'id', 'stage');
    }
}
