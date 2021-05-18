{{ Form::model($file, array('route' => array('project.file.update', $project_id,$file->id),'enctype'=>"multipart/form-data", 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('file', __('File')) }}
        {{ Form::file('file', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


