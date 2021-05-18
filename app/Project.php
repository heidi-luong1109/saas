<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'price',
        'client',
        'start_date',
        'due_date',
        'description',
        'label',
        'lead',
        'status',
        'created_by',
    ];

    public static $status        = [
        'incomplete' => 'Incomplete',
        'complete' => 'Complete',
    ];
    public static $priority      = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];
    public static $projectStatus = [
        'not_started' => 'Not Started',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'canceled' => 'Canceled',
        'finished' => 'Finished',
    ];

    public function projectUser()
    {
        return ProjectUser::select('project_users.*', 'users.name', 'users.avatar', 'users.email', 'users.type')->join('users', 'users.id', '=', 'project_users.user_id')->where('project_id', '=', $this->id)->whereNotIn('user_id', [$this->created_by])->get();
    }

    public function clients()
    {
        return $this->hasOne('App\User', 'id', 'client');
    }

    public function activities()
    {
        return $this->hasMany('App\ProjectActivityLog', 'project_id', 'id')->orderBy('id', 'desc');
    }

    public function tasks()
    {
        return $this->hasMany('App\ProjectTask', 'project_id', 'id');
    }

    public function milestones()
    {
        return $this->hasMany('App\ProjectMilestone', 'project_id', 'id');
    }

    public function user_project_total_task($project_id, $user_id)
    {
        return ProjectTask::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->count();
    }

    public function user_project_comlete_task($project_id, $user_id, $last_stage_id)
    {
        return ProjectTask::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->where('stage', '=', $last_stage_id)->count();
    }

    public function project_last_stage()
    {
        return ProjectStage::where('created_by', '=', $this->created_by)->orderBy('order', 'desc')->first();
    }

    public function taskFilter($status, $priority, $dueDate)
    {
        $tasks = ProjectTask::where('project_id', $this->id);

        if(!empty($status))
        {
            $tasks->where('stage', $status);
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

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'project', 'id');
    }

    public function tasksFilter()
    {
        $tasks = ProjectTask::where('project_id', $this->id);

        if((isset($_GET['start_date']) && !empty($_GET['start_date'])) && (isset($_GET['end_date']) && !empty($_GET['end_date'])))
        {
            $tasks->whereBetween(
                'start_date', [
                                $_GET['start_date'],
                                $_GET['end_date'],
                            ]
            );
        }
        else
        {
            $end_date   = date('Y-m-d');
            $start_date = date('Y-m-d', strtotime('-30 days'));

            $tasks->whereBetween(
                'start_date', [
                                $start_date,
                                $end_date,
                            ]
            );
        }


        if(isset($_GET['employee']) && !empty($_GET['employee']))
        {
            $tasks->where('assign_to', $_GET['employee']);
        }

        $tasks = $tasks->get();

        return $tasks;
    }

    public function dueTask()
    {
        $tasks = ProjectTask::where('project_id', $this->id)->where('due_date', '<', date('Y-m-d'))->count();

        return $tasks;
    }

    public function userTasks()
    {
        $tasks = ProjectTask::where('project_id', $this->id)->where('assign_to', \Auth::user()->id)->get();

        return $tasks;
    }

    public function completedTask()
    {
        $stage = ProjectStage::orderBy('order', 'desc')->first();

        return ProjectTask::where('project_id', $this->id)->where('stage', !empty($stage) ? $stage->id : 0)->count();
    }
    public function totalExpense()
    {
        return Expense::where('project', $this->id)->sum('amount');
    }
}
