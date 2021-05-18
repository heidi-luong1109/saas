{{ Form::model($note, array('route' => array('project.note.update', $project_id,$note->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'5']) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


