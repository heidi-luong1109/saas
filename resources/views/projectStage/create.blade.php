{{ Form::open(array('url' => 'projectStage')) }}
<div class="row">
    <div class="form-group col-md-8">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-4">
        {{ Form::label('color', __('Color')) }}
        {{ Form::color('color', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
