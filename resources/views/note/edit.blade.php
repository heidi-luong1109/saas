{{ Form::model($note,array('route' => array('note.update',$note->id),'method'=>'PUT','enctype'=>"multipart/form-data")) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('title', __('Title')) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('file', __('File')) }}
        {{ Form::file('file', array('class' => 'form-control')) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
