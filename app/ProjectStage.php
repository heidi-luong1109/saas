<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
{
    protected $fillable = [
        'name',
        'order',
        'created_by',
    ];

    public function tasks($project_id)
    {
        if(\Auth::user()->type == 'client' || \Auth::user()->type == 'company')
        {
            return ProjectTask::where('stage', '=', $this->id)->where('project_id', '=', $project_id)->orderBy('order')->get();
        }
        else
        {
            return ProjectTask::where('stage', '=', $this->id)->where('assign_to', '=', \Auth::user()->id)->where('project_id', '=', $project_id)->orderBy('order')->get();
        }
    }

    public function allTask()
    {
        return $this->hasMany('App\ProjectTask', 'stage', 'id');
    }

    public function allTaskFilter($project, $priority, $dueDate)
    {
        $tasks = ProjectTask::where('stage', $this->id);

        if(!empty($project))
        {
            $tasks->where('project_id', $project);
        }
        if(!empty($priority))
        {
            $tasks->where('priority', $priority);
        }
        if(!empty($dueDate))
        {
            $tasks->where('due_date', $dueDate);
        }
        $tasks = $tasks->get();

        return $tasks;

    }


}
