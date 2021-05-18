{{ Form::model($projectStage, array('route' => array('projectStage.update', $projectStage->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-8">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-4">
        {{ Form::label('color', __('Color')) }}
        {{ Form::color('color', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}

