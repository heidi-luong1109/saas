<?php

namespace App\Http\Controllers;

use App\Category;
use App\CheckList;
use App\Expense;
use App\Invoice;
use App\Label;
use App\Lead;
use App\Project;
use App\ProjectActivityLog;
use App\ProjectClientFeedback;
use App\ProjectComment;
use App\ProjectFile;
use App\ProjectMilestone;
use App\ProjectNote;
use App\ProjectStage;
use App\ProjectTask;
use App\ProjectTaskCheckList;
use App\ProjectTaskComment;
use App\ProjectTaskFile;
use App\ProjectUser;
use App\Timesheet;
use App\User;
use App\Utility;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client' || \Auth::user()->type == 'employee')
        {
            $user = \Auth::user();
            if(\Auth::user()->type == 'client')
            {
                $projects = Project::where('client', '=', $user->id)->get();
            }
            elseif(\Auth::user()->type == 'employee')
            {
                $projects = Project::select('projects.*')->leftjoin('project_users', 'project_users.project_id', 'projects.id')->where('project_users.user_id', '=', $user->id)->get();
            }
            else
            {
                $projects = Project::where('created_by', '=', $user->creatorId())->get();
            }


            $projectStatus = Project::$projectStatus;


            return view('project.index', compact('projects', 'projectStatus'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
        $clients   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get()->pluck('name', 'id');
        $clients->prepend('Select Client', '');
        $labels = Label::where('created_by', '=', \Auth::user()->creatorId())->get();
        $labels->prepend('Select Lead', '');
        $leads = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $leads->prepend('Select Lead', 0);
        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 2)->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');
        $projectStatus = Project::$projectStatus;

        return view('project.create', compact('clients', 'labels', 'employees', 'leads', 'categories', 'projectStatus'));
    }


    public function store(Request $request)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'category' => 'required',
                                   'price' => 'required',
                                   'start_date' => 'required',
                                   'due_date' => 'required',
                                   'employee' => 'required',
                                   'status' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.index')->with('error', $messages->first());
            }

            $projectStages = ProjectStage::where('created_by', \Auth::user()->creatorId())->first();
            if(empty($projectStages))
            {
                return redirect()->route('project.index')->with('error', 'Please add constant project stage.');
            }
            $project              = new Project();
            $project->title       = $request->title;
            $project->category    = $request->category;
            $project->price       = $request->price;
            $project->start_date  = $request->start_date;
            $project->due_date    = $request->due_date;
            $project->lead        = $request->lead;
            $project->client      = $request->client;
            $project->status      = $request->status;
            $project->description = $request->description;
            $project->created_by  = \Auth::user()->creatorId();
            $project->save();

            $projectUser             = new ProjectUser();
            $projectUser->user_id    = \Auth::user()->creatorId();
            $projectUser->project_id = $project->id;
            $projectUser->save();

            foreach($request->employee as $key => $user)
            {
                $projectUser             = new ProjectUser();
                $projectUser->user_id    = $user;
                $projectUser->project_id = $project->id;
                $projectUser->save();
            }

            $client     = User::find($request->client);
            $user       = \Auth::user();
            $projectArr = [
                'project_title' => $project->title,
                'project_category' => !empty(Category::find($project->category)) ? Category::find($project->category)->name : '',
                'project_price' => $user->priceFormat($project->price),
                'project_client' => $client->name,
                'project_start_date' => $user->dateFormat($project->start_date),
                'project_due_date' => $user->dateFormat($project->due_date),
                'project_lead' => !empty(Lead::find(!empty($request->lead) ? $request->lead : 0)) ? Lead::find(!empty($request->lead) ? Lead::find(!empty($request->lead))->subject : 0) : '',
            ];

            // Send Email
            $resp = Utility::sendEmailTemplate('create_project', [$client->id => $client->email], $projectArr);

            foreach($request->employee as $key => $emp)
            {
                $employee         = User::find($emp);
                $projectAssignArr = [
                    'project_title' => $project->title,
                    'project_category' => !empty(Category::find($project->category)) ? Category::find($project->category)->name : '',
                    'project_price' => $user->priceFormat($project->price),
                    'project_client' => $client->name,
                    'project_assign_user' => $employee->name,
                    'project_start_date' => $user->dateFormat($project->start_date),
                    'project_due_date' => $user->dateFormat($project->due_date),
                    'project_lead' => !empty(Lead::find(!empty($request->lead) ? $request->lead : 0)) ? Lead::find(!empty($request->lead) ? Lead::find(!empty($request->lead))->subject : 0) : '',
                ];


                $resp = Utility::sendEmailTemplate('project_assign', [$employee->id => $employee->email], $projectAssignArr);
            }


            return redirect()->route('project.index')->with('success', __('Project successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        $id      = \Crypt::decrypt($ids);
        $project = Project::find($id);

        $projectStatus = Project::$projectStatus;

        // For Task
        $stages     = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
        $milestones = ProjectMilestone::where('project_id', $id)->get();
        $notes      = ProjectNote::where('project_id', $id)->get();
        $files      = ProjectFile::where('project_id', $id)->get();
        $comments   = ProjectComment::where('project_id', $id)->where('parent', 0)->get();
        $feedbacks  = ProjectClientFeedback::where('project_id', $id)->where('parent', 0)->get();
        $timesheets = Timesheet::where('project_id', $id)->get();


        $future     = strtotime($project->due_date);
        $timefromdb = strtotime(date('Y-m-d'));
        $timeleft   = $future - $timefromdb;
        $daysleft   = round((($timeleft / 24) / 60) / 60);

        $totalExpense = Expense::where('project', $project->id)->sum('amount');
        $invoices     = Invoice::where('project', $id)->where('type', 'Project')->get();

        return view('project.show', compact('project', 'projectStatus', 'stages', 'milestones', 'notes', 'files', 'comments', 'feedbacks', 'timesheets', 'invoices', 'daysleft', 'totalExpense'));
    }


    public function edit($ids)
    {
        $id      = \Crypt::decrypt($ids);
        $project = Project::find($id);

        $employees = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->get()->pluck('name', 'id');
        $clients   = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'client')->get()->pluck('name', 'id');
        $clients->prepend('Select Client', '');
        $labels = Label::where('created_by', '=', \Auth::user()->creatorId())->get();
        $labels->prepend('Select Lead', '');
        $leads = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $leads->prepend('Select Lead', 0);
        $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 2)->get()->pluck('name', 'id');
        $categories->prepend('Select Category', '');
        $projectStatus = Project::$projectStatus;

        return view('project.edit', compact('clients', 'labels', 'employees', 'leads', 'categories', 'projectStatus', 'project'));
    }


    public function update(Request $request, Project $project)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'category' => 'required',
                                   'price' => 'required',
                                   'start_date' => 'required',
                                   'due_date' => 'required',
                                   'status' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.index')->with('error', $messages->first());
            }


            $project->title       = $request->title;
            $project->category    = $request->category;
            $project->price       = $request->price;
            $project->start_date  = $request->start_date;
            $project->due_date    = $request->due_date;
            $project->lead        = $request->lead;
            $project->client      = $request->client;
            $project->status      = $request->status;
            $project->description = $request->description;
            $project->save();


            return redirect()->route('project.index')->with('success', __('Project successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Project $project)
    {
        if(\Auth::user()->type == 'company')
        {

            $project->delete();

            return redirect()->route('project.index')->with('success', __('Project successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function projectUser($id)
    {
        $assign_user = ProjectUser::select('user_id')->where('project_id', $id)->get()->pluck('user_id');
        $employee    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'employee')->whereNotIn('id', $assign_user)->get()->pluck('name', 'id');
        $employee->prepend('Select User', '');

        return view('project.userAdd', compact('employee', 'id'));
    }

    public function addProjectUser(Request $request, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'user' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($id))->with('error', $messages->first());
            }

            foreach($request->user as $key => $user)
            {
                $projectUser             = new ProjectUser();
                $projectUser->user_id    = $user;
                $projectUser->project_id = $id;
                $projectUser->save();

            }

            return redirect()->route('project.show', \Crypt::encrypt($id))->with('success', __('User successfully added.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroyProjectUser($projectId, $userId)
    {
        if(\Auth::user()->type == 'company')
        {
            $projectUser = ProjectUser::where('project_id', $projectId)->where('user_id', $userId)->first();
            $projectUser->delete();

            return redirect()->route('project.show', \Crypt::encrypt($projectId))->with('success', __('User successfully deleted from project.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function changeStatus(Request $request, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $status         = Project::find($id);
            $status->status = $request->status;
            $status->save();

            if($request->status == 'finished')
            {
                $client     = User::find($status->client);
                $user       = \Auth::user();
                $projectArr = [
                    'project_name' => $status->title,
                    'project_category' => !empty(Category::find($status->category)) ? Category::find($status->category)->name : '',
                    'project_price' => $user->priceFormat($status->price),
                    'project_client' => $client->name,
                    'project_start_date' => $user->dateFormat($status->start_date),
                    'project_due_date' => $user->dateFormat($status->due_date),
                    'project_lead' => !empty(Lead::find(!empty($request->lead) ? $request->lead : 0)) ? Lead::find(!empty($request->lead) ? $request->lead->subject : 0) : '',
                ];

                // Send Email
                $resp = Utility::sendEmailTemplate('project_finished', [$client->id => $client->email], $projectArr);

                return redirect()->back()->with('success', __('Project status successfully change.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }


            return redirect()->back()->with('success', __('Project status successfully change.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    // For Project Task

    public function taskCreate($project_id)
    {
        $project  = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        $projects = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $projects->prepend('Select Project', '');
        $priority = Project::$priority;

        if($project_id == 0)
        {
            $milestones = [];
            $users      = [];

        }
        else
        {
            $usersArr = ProjectUser::where('project_id', '=', $project_id)->get();
            $users    = array();
            foreach($usersArr as $user)
            {
                if(!empty($user->projectUsers))
                {
                    $users[$user->projectUsers->id] = ($user->projectUsers->name . ' - ' . $user->projectUsers->email);
                }

            }

            $milestones = ProjectMilestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        }


        return view('project.taskCreate', compact('project', 'projects', 'priority', 'users', 'milestones', 'project_id'));
    }

    public function taskStore(Request $request, $projec_id)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'start_date' => 'required',
                                   'due_date' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $usr = \Auth::user();


            $post = $request->all();
            if($usr->type != 'company')
            {
                $post['assign_to'] = $usr->id;
            }

            if($projec_id == 0)
            {
                $post['project_id'] = $request->project;
            }
            else
            {
                $post['project_id'] = $projec_id;
            }

            if($request->milestone_id == '')
            {
                $post['milestone_id'] = 0;
            }

            $post['stage'] = !empty(ProjectStage::where('created_by', '=', $usr->creatorId())->first()) ? ProjectStage::where('created_by', '=', $usr->creatorId())->first()->id : 0;

            $task = ProjectTask::create($post);

            $task = ProjectTask::find($task->id);

            ProjectActivityLog::create(
                [
                    'user_id' => $usr->creatorId(),
                    'project_id' => ($projec_id == 0) ? $request->project : $projec_id,
                    'log_type' => 'Create Task',
                    'remark' => json_encode(['title' => $task->title]),
                ]
            );


            $employee = User::find($task->assign_to);
            $user     = \Auth::user();
            $taskArr  = [
                'project' => !empty(Project::find($post['project_id'])) ? Project::find($post['project_id'])->title : '',
                'task_title' => $task->title,
                'task_priority' => Project::$priority[$task->priority],
                'task_start_date' => $user->dateFormat($task->start_date),
                'task_due_date' => $user->dateFormat($task->due_date),
                'task_stage' => !empty(ProjectStage::find($task->stage)) ? ProjectStage::find($task->stage)->name : '',
                'task_assign_user' => $employee->name,
                'task_description' => $task->description,
            ];

            // Send Email
            $resp = Utility::sendEmailTemplate('task_assign', [$employee->id => $employee->email], $taskArr);

            return redirect()->back()->with('success', __('Task successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function taskEdit($task_id)
    {
        $task       = ProjectTask::find($task_id);
        $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $usersArr   = ProjectUser::where('project_id', '=', $task->project_id)->get();
        $priority   = Project::$priority;
        $milestones = [];
        //        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $users = array();
        foreach($usersArr as $user)
        {
            if(!empty($user->projectUsers))
            {
                $users[$user->projectUsers->id] = ($user->projectUsers->name . ' - ' . $user->projectUsers->email);
            }

        }

        return view('project.taskEdit', compact('project', 'projects', 'users', 'task', 'priority', 'milestones'));
    }

    public function taskUpdate(Request $request, $task_id)
    {

        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                                   //                                   'milestone_id' => 'required',
                               ]
            );
        }

        $task    = ProjectTask::find($task_id);
        $project = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $task->project_id)->first();
        if($project)
        {
            $post               = $request->all();
            $post['project_id'] = $task->project_id;
            $task->update($post);

            return redirect()->back()->with('success', __('Task Updated Successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('You can \'t Edit Task!'));
        }
    }

    public function taskDestroy($task_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $task    = ProjectTask::find($task_id);
            $project = Project::find($task->project_id);
            if($project->created_by == \Auth::user()->creatorId())
            {
                $task->delete();

                return redirect()->back()->with('success', __('Task successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('You can\'t Delete Task.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function order(Request $request)
    {
        $post  = $request->all();
        $task  = ProjectTask::find($post['task_id']);
        $stage = ProjectStage::find($post['stage_id']);


        if(!empty($stage))
        {
            $task->stage = $post['stage_id'];
            $task->save();
        }

        foreach($post['order'] as $key => $item)
        {
            $task_order        = ProjectTask::find($item);
            $task_order->order = $key;
            $task_order->stage = $post['stage_id'];
            $task_order->save();
        }

    }

    public function taskShow($task_id, $client_id = '')
    {

        $task    = ProjectTask::find($task_id);
        $project = Project::find($task->project_id);

        return view('project.taskShow', compact('task'));
    }


    public function checkListStore(Request $request, $task_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $request->validate(
                ['name' => 'required']
            );
            $post['task_id']      = $task_id;
            $post['name']         = $request->name;
            $post['created_by']   = \Auth::user()->creatorId();
            $CheckList            = ProjectTaskCheckList::create($post);
            $CheckList->deleteUrl = route(
                'project.task.checklist.destroy', [
                                                    $CheckList->task_id,
                                                    $CheckList->id,
                                                ]
            );
            $CheckList->updateUrl = route(
                'project.task.checklist.update', [
                                                   $CheckList->task_id,
                                                   $CheckList->id,
                                               ]
            );

            return $CheckList->toJson();
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function checklistDestroy(Request $request, $task_id, $checklist_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $checklist = ProjectTaskCheckList::find($checklist_id);
            $checklist->delete();

            return "true";
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function checklistUpdate($task_id, $checklist_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
        {
            $checkList = ProjectTaskCheckList::find($checklist_id);
            if($checkList->status == 0)
            {
                $checkList->status = 1;
            }
            else
            {
                $checkList->status = 0;
            }
            $checkList->save();

            return $checkList->toJson();
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function commentStore(Request $request, $project_id, $task_id)
    {

        $post               = [];
        $post['task_id']    = $task_id;
        $post['comment']    = $request->comment;
        $post['created_by'] = \Auth::user()->authId();
        $post['user_type']  = \Auth::user()->type;
        $comment            = ProjectTaskComment::create($post);

        $comment->deleteUrl = route('project.task.comment.destroy', [$comment->id]);

        return $comment->toJson();
    }

    public function commentDestroy($comment_id)
    {
        $comment = ProjectTaskComment::find($comment_id);
        $comment->delete();

        return "true";
    }

    public function commentStoreFile(Request $request, $task_id)
    {
        $request->validate(
            ['file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048']
        );
        $fileName = $task_id . time() . "_" . $request->file->getClientOriginalName();

        $request->file->storeAs('uploads/tasks', $fileName);
        $post['task_id']    = $task_id;
        $post['file']       = $fileName;
        $post['name']       = $request->file->getClientOriginalName();
        $post['extension']  = "." . $request->file->getClientOriginalExtension();
        $post['file_size']  = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        $post['created_by'] = \Auth::user()->creatorId();
        $post['user_type']  = \Auth::user()->type;

        $TaskFile            = ProjectTaskFile::create($post);
        $TaskFile->deleteUrl = route('project.task.comment.file.destroy', [$TaskFile->id]);

        return $TaskFile->toJson();
    }

    public function commentDestroyFile(Request $request, $file_id)
    {
        $commentFile = ProjectTaskFile::find($file_id);
        $path        = storage_path('uploads/tasks/' . $commentFile->file);
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $commentFile->delete();

        return "true";
    }

    public function milestone($project_id)
    {
        $project = Project::find($project_id);
        $status  = Project::$status;

        return view('project.milestoneCreate', compact('project', 'status'));
    }

    public function milestoneStore(Request $request, $project_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'status' => 'required',
                                   'cost' => 'required',
                                   'due_date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }

            $milestone              = new ProjectMilestone();
            $milestone->project_id  = $project->id;
            $milestone->title       = $request->title;
            $milestone->status      = $request->status;
            $milestone->cost        = $request->cost;
            $milestone->due_date    = $request->due_date;
            $milestone->description = $request->description;
            $milestone->save();

            ProjectActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project->id,
                    'log_type' => 'Create Milestone',
                    'remark' => json_encode(['title' => $milestone->title]),
                ]
            );


            return redirect()->back()->with('success', __('Milestone successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function milestoneEdit($id)
    {
        $milestone = ProjectMilestone::find($id);
        $status    = Project::$status;

        return view('project.milestoneEdit', compact('milestone', 'status'));
    }

    public function milestoneUpdate($id, Request $request)
    {
        if(\Auth::user()->type == 'company')
        {
            $milestone = ProjectMilestone::find($id);
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'status' => 'required',
                                   'cost' => 'required',
                                   'due_date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($milestone->project_id))->with('error', $messages->first());
            }


            $milestone->title       = $request->title;
            $milestone->status      = $request->status;
            $milestone->cost        = $request->cost;
            $milestone->due_date    = $request->due_date;
            $milestone->description = $request->description;
            $milestone->save();

            return redirect()->back()->with('success', __('Milestone successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function milestoneDestroy($id)
    {

        $milestone = ProjectMilestone::find($id);
        $milestone->delete();

        return redirect()->back()->with('success', __('Milestone successfully deleted.'));
    }


    public function notes($project_id)
    {
        $project = Project::find($project_id);

        return view('project.noteCreate', compact('project'));
    }

    public function noteStore(Request $request, $project_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }


            $notes              = new ProjectNote();
            $notes->project_id  = $project->id;
            $notes->title       = $request->title;
            $notes->description = $request->description;
            $notes->save();

            ProjectActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project->id,
                    'log_type' => 'Create Notes',
                    'remark' => json_encode(['title' => $notes->title]),
                ]
            );


            return redirect()->back()->with('success', __('Notes successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function noteEdit($project_id, $note_id)
    {
        $note = ProjectNote::find($note_id);

        return view('project.noteEdit', compact('note', 'project_id'));
    }

    public function noteUpdate(Request $request, $project_id, $note_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }


            $notes              = ProjectNote::find($note_id);
            $notes->title       = $request->title;
            $notes->description = $request->description;
            $notes->save();


            return redirect()->back()->with('success', __('Notes successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function noteDestroy($project_id, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $note = ProjectNote::find($id);
            $note->delete();

            return redirect()->back()->with('success', __('Note successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function file($project_id)
    {
        $project = Project::find($project_id);

        return view('project.fileCreate', compact('project'));
    }

    public function fileStore(Request $request, $project_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'file' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }

            $fileName = time() . "_" . $request->file->getClientOriginalName();

            $request->file->storeAs('uploads/files', $fileName);

            $notes              = new ProjectFile();
            $notes->project_id  = $project->id;
            $notes->file        = $fileName;
            $notes->description = $request->description;
            $notes->save();

            ProjectActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project->id,
                    'log_type' => 'Uploads Files',
                    'remark' => json_encode(['title' => 'Project file uploads']),
                ]
            );


            return redirect()->back()->with('success', __('File successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileEdit($project_id, $note_id)
    {
        $file = ProjectFile::find($note_id);

        return view('project.fileEdit', compact('file', 'project_id'));
    }

    public function fileUpdate(Request $request, $project_id, $file_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {

            $file = ProjectFile::find($file_id);
            if(!empty($request->file))
            {
                $fileName = time() . "_" . $request->file->getClientOriginalName();

                $request->file->storeAs('uploads/files', $fileName);

                $file->file = $fileName;
            }


            $file->description = $request->description;
            $file->save();

            return redirect()->back()->with('success', __('File successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function fileDestroy($id)
    {

        $file = ProjectFile::find($id);
        $file->delete();

        return redirect()->back()->with('success', __('File successfully deleted.'));
    }


    public function projectCommentStore(Request $request, $project_id)
    {

        $project = Project::find($project_id);

        $validator = \Validator::make(
            $request->all(), [
                               'comment' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
        }

        if(!empty($request->file))
        {
            $fileName = time() . "_" . $request->file->getClientOriginalName();
            $request->file->storeAs('uploads/files', $fileName);
        }


        $comments             = new ProjectComment();
        $comments->project_id = $project->id;
        $comments->file       = !empty($fileName) ? $fileName : '';
        $comments->comment    = $request->comment;
        $comments->comment_by = \Auth::user()->id;
        $comments->parent     = !empty($request->parent) ? $request->parent : 0;
        $comments->save();

        ProjectActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Comment Create',
                'remark' => json_encode(['title' => 'Project comment Post']),
            ]
        );


        return redirect()->back()->with('success', __('Comment successfully posted.'));
    }

    public function projectCommentReply($project_id, $comment_id)
    {

        return view('project.commentReply', compact('project_id', 'comment_id'));
    }


    public function projectClientFeedbackStore(Request $request, $project_id)
    {
        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'feedback' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }

            if(!empty($request->file))
            {
                $fileName = time() . "_" . $request->file->getClientOriginalName();
                $request->file->storeAs('uploads/files', $fileName);
            }


            $feedback              = new ProjectClientFeedback();
            $feedback->project_id  = $project->id;
            $feedback->file        = !empty($fileName) ? $fileName : '';
            $feedback->feedback    = $request->feedback;
            $feedback->feedback_by = \Auth::user()->id;
            $feedback->parent      = !empty($request->parent) ? $request->parent : 0;
            $feedback->save();

            ProjectActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project->id,
                    'log_type' => 'Feedback Create',
                    'remark' => json_encode(['title' => 'Project comment post']),
                ]
            );


            return redirect()->back()->with('success', __('Feedback successfully posted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function projectClientFeedbackReply($project_id, $comment_id)
    {

        return view('project.clientFeedbackReply', compact('project_id', 'comment_id'));
    }


    public function projectTimesheet($project_id)
    {
        $project = Project::find($project_id);
        if($project_id == 0)
        {
            $users = [];
            $tasks = [];
        }
        else
        {
            $users = $project->projectUser();
            $tasks = $project->tasks;
        }

        $projectList = Project::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $projectList->prepend('Select Project', '');

        return view('project.timesheetCreate', compact('project', 'users', 'tasks', 'project_id', 'projectList'));
    }

    public function projectTimesheetStore(Request $request, $project_id)
    {

        if(\Auth::user()->type == 'company')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'employee' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );

            if($project_id == 0)
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'project' => 'required',
                                   ]
                );
            }
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $timesheet = new Timesheet();
            if($project_id == 0)
            {
                $timesheet->project_id = $request->project;
                $timesheet->task_id    = !empty($request->task) ? $request->task : 0;
            }
            else
            {
                $timesheet->project_id = $project->id;
                $timesheet->task_id    = $request->task_id;
            }

            $timesheet->employee   = $request->employee;
            $timesheet->start_date = $request->start_date;
            $timesheet->start_time = $request->start_time;
            $timesheet->end_date   = $request->end_date;
            $timesheet->end_time   = $request->end_time;

            $timesheet->notes      = $request->notes;
            $timesheet->created_by = \Auth::user()->creatorId();
            $timesheet->save();

            ProjectActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => ($project_id == 0) ? $request->project : $project->id,
                    'log_type' => 'Create Timesheet',
                    'remark' => json_encode(['title' => $timesheet->notes]),
                ]
            );


            return redirect()->back()->with('success', __('Timesheet successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function projectTimesheetEdit($project_id, $id)
    {
        $timesheet = Timesheet::find($id);
        $project   = Project::find($project_id);
        $users     = $project->projectUser();
        $tasks     = $project->tasks;

        return view('project.timesheetEdit', compact('project', 'users', 'tasks', 'timesheet'));
    }


    public function projectTimesheetUpdate(Request $request, $project_id, $id)
    {
        if(\Auth::user()->type == 'company')
        {
            $project = Project::find($project_id);

            $validator = \Validator::make(
                $request->all(), [
                                   'employee' => 'required',
                                   'start_date' => 'required',
                                   'start_time' => 'required',
                                   'end_date' => 'required',
                                   'end_time' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('project.show', \Crypt::encrypt($project_id))->with('error', $messages->first());
            }

            $timesheet             = Timesheet::find($id);
            $timesheet->project_id = $project->id;
            $timesheet->employee   = $request->employee;
            $timesheet->start_date = $request->start_date;
            $timesheet->start_time = $request->start_time;
            $timesheet->end_date   = $request->end_date;
            $timesheet->end_time   = $request->end_time;
            $timesheet->task_id    = $request->task_id;
            $timesheet->notes      = $request->notes;
            $timesheet->save();


            return redirect()->back()->with('success', __('Timesheet successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function projectTimesheetNote($project_id, $id)
    {
        $timesheet = Timesheet::find($id);

        return view('project.timesheetNote', compact('timesheet'));
    }

    public function projectTimesheetDestroy($id)
    {

        if(\Auth::user()->type == 'company')
        {
            $timesheet = Timesheet::find($id);
            $timesheet->delete();

            return redirect()->back()->with('success', __('Timesheet successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    //    For All Project Task

    public function allTask(Request $request)
    {


        $priority  = Project::$priority;
        $stageList = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $stages = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC');

        if(!empty($request->status))
        {
            $stages->where('id', $request->status);
        }
        $stages = $stages->get();

        if(\Auth::user()->type == 'company')
        {
            $projects = $projectList = Project::where('created_by', \Auth::user()->creatorId());

        }
        elseif(\Auth::user()->type == 'employee')
        {
            $projects = $projectList = Project::select('projects.*', 'project_users.user_id')->leftJoin(
                'project_users', function ($join){
                $join->on('projects.id', '=', 'project_users.project_id');
                $join->where('project_users.user_id', \Auth::user()->id);
            }
            )->where('created_by', \Auth::user()->creatorId());

        }
        else
        {
            $projects = $projectList = Project::where('client', \Auth::user()->id);
        }

        if(!empty($request->project))
        {
            $projects->where('id', $request->project);
        }

        $projectList = Project::where('created_by', \Auth::user()->creatorId())->get()->pluck('title', 'id');
        $projectList->prepend('All', '');

        $projects = $projects->get();


        return view('project.allTask', compact('stages', 'projects', 'projectList', 'priority', 'stageList'));

    }

    public function getMilestone(Request $request)
    {
        $milestones = ProjectMilestone::where('project_id', $request->project_id)->get()->pluck('title', 'id');

        return response()->json($milestones);
    }

    public function getUser(Request $request)
    {
        $usersArr = ProjectUser::orderBy('id');
        if(!empty($request->project_id))
        {
            $usersArr->where('project_id', '=', $request->project_id);
        }
        $usersArr = $usersArr->get();
        $users    = array();
        foreach($usersArr as $user)
        {
            $users[$user->projectUsers->id] = ($user->projectUsers->name . ' - ' . $user->projectUsers->email);
        }

        return response()->json($users);
    }

    //    For All Project Task

    public function allTimesheet(Request $request)
    {


        if(\Auth::user()->type == 'company')
        {
            $projectList = Project::where('created_by', \Auth::user()->creatorId());

        }
        elseif(\Auth::user()->type == 'employee')
        {
            $projectList = Project::select('projects.*', 'project_users.user_id')->leftJoin(
                'project_users', function ($join){
                $join->on('projects.id', '=', 'project_users.project_id');
                $join->where('project_users.user_id', \Auth::user()->id);
            }
            )->where('created_by', \Auth::user()->creatorId());

        }

        if(\Auth::user()->type == 'company')
        {
            $timesheet = Timesheet::where('created_by', \Auth::user()->creatorId());
        }
        else
        {
            $timesheet = Timesheet::where('employee', \Auth::user()->id);
        }


        if(!empty($request->project))
        {
            $timesheet->where('project_id', $request->project);
        }
        if(!empty($request->task))
        {
            $timesheet->where('task_id', $request->task);
        }
        if(!empty($request->user))
        {
            $timesheet->where('employee', $request->user);
        }
        if(!empty($request->start_date))
        {
            $timesheet->where('start_date', '>=', $request->start_date);
        }
        if(!empty($request->end_date))
        {
            $timesheet->where('end_date', '<=', $request->end_date);
        }

        $timesheet = $timesheet->get();

        $projectList = $projectList->get()->pluck('title', 'id');
        $projectList->prepend('All', '');

        return view('project.allTimesheet', compact('timesheet', 'projectList'));
    }

    public function getTask(Request $request)
    {
        $tasks = ProjectTask::orderBy('id');
        if(!empty($request->project_id))
        {
            $tasks->where('project_id', $request->project_id);
        }
        $tasks = $tasks->get()->pluck('title', 'id');

        return response()->json($tasks);
    }
}
