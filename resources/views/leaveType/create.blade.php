{{ Form::open(array('url' => 'leaveType')) }}
<div class="form-group">
    {{ Form::label('title', __('Title')) }}
    {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{Form::label('days',__('Days Per Year'))}}
    {{Form::number('days',null,array('class'=>'form-control'))}}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
