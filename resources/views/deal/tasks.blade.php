@if(isset($task))
    {{ Form::model($task, array('route' => array('deal.tasks.update', $deal->id, $task->id), 'method' => 'PUT')) }}
@else
    {{ Form::open(array('route' => ['deal.tasks.store',$deal->id])) }}
@endif
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('date', __('Date')) }}
            {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('time', __('Time')) }}
            {{ Form::time('time', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="form-group">
    {{ Form::label('priority', __('Priority')) }}
    <select class="form-control custom-select" name="priority" required>
        @foreach($priorities as $key => $priority)
            <option value="{{$key}}" @if(isset($task) && $task->priority == $key) selected @endif>{{__($priority)}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {{ Form::label('status', __('Status')) }}
    <select class="form-control custom-select" name="status" required>
        @foreach($status as $key => $st)
            <option value="{{$key}}" @if(isset($task) && $task->status == $key) selected @endif>{{__($st)}}</option>
        @endforeach
    </select>
</div>

@if(isset($task))
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
@else
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
@endif
{{ Form::close() }}

