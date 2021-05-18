{{ Form::model($dealStage, array('route' => array('dealStage.update', $dealStage->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('name', __('Pipeline')) }}
    {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control custom-select','required'=>'required')) }}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}

