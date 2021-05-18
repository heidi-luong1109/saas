{{ Form::model($timesheet, array('route' => array('project.timesheet.update', [$project->id,$timesheet->id]), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('employee', __('User')) }}
        <select class="form-control custom-select" required="required" name="employee">
            @foreach($users as $user)
                @if(!empty($user->projectUsers))
                    <option value="{{$user->projectUsers->id}}" {{($timesheet->employee==$user->projectUsers->id)?'selected':''}}>{{$user->projectUsers->name}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('start_time', __('Start Time')) }}
        {{ Form::time('start_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('end_date', __('End Date')) }}
        {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('end_time', __('End Time')) }}
        {{ Form::time('end_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('task_id', __('Task')) }}
        <select class="form-control custom-select"  name="task_id">
            <option value="0">{{__('-')}}</option>
            @foreach($tasks as $task)
                @if(!empty($task))
                    <option value="{{$task->id}}" {{($timesheet->task_id==$task->id)?'selected':''}}>{{$task->title}}</option>
                @endif
            @endforeach
        </select>
    </div>
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
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


