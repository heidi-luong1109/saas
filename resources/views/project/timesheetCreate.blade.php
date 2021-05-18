@if($project_id==0)
    {{ Form::open(array('route' => array('project.timesheet.store',0))) }}
@else
    {{ Form::open(array('route' => array('project.timesheet.store',$project_id))) }}
@endif
<div class="row">
    @if($project_id==0)
        <div class="form-group col-md-6">
            {{ Form::label('project', __('Project')) }}
            {{ Form::select('project', $projectList,null, array('class' => 'form-control custom-select','id'=>'project_id')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('user', __('User')) }}
            <select class="form-control custom-select user" name="employee" id="users">

            </select>
        </div>
    @else
        <div class="form-group  col-md-12">
            {{ Form::label('employee', __('User')) }}
            <select class="form-control custom-select" required="required" name="employee">
                @foreach($users as $user)
                    @if(!empty($user->projectUsers))
                        <option value="{{$user->projectUsers->id}}">{{$user->projectUsers->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    @endif

    <div class="form-group  col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{ Form::date('start_date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('start_time', __('Start Time')) }}
        {{ Form::time('start_time', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('end_date', __('End Date')) }}
        {{ Form::date('end_date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('end_time', __('End Time')) }}
        {{ Form::time('end_time', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    @if($project_id==0)
        <div class="form-group col-md-6">
            {{ Form::label('task', __('Task')) }}
            <select class="form-control custom-select user" name="task" id="task_id">

            </select>
        </div>
    @else
        <div class="form-group  col-md-12">
            {{ Form::label('task_id', __('Task')) }}
            <select class="form-control custom-select" name="task_id">
                <option value="0">{{__('-')}}</option>
                @foreach($tasks as $task)
                    @if(!empty($task))
                        <option value="{{$task->id}}">{{$task->title}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    @endif
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('notes', __('Notes')) }}
        {!! Form::textarea('notes', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


