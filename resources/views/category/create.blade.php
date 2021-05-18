{{ Form::open(array('url' => 'category')) }}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('type', __('Category Type')) }}
    {{ Form::select('type',$types,null, array('class' => 'form-control custom-select ','required'=>'required')) }}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
