{{ Form::open(array('url' => 'designation')) }}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('department', __('Department')) }}
    {{ Form::select('department', $department,null, array('class' => 'form-control custom-select','required'=>'required')) }}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
