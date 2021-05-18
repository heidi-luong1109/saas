{{ Form::model($leaveType, array('route' => array('leaveType.update', $leaveType->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('title', __('Title')) }}
    {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{Form::label('days',__('Days Per Year'))}}
    {{Form::number('days',null,array('class'=>'form-control'))}}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
