{{ Form::open(array('route' => array('project.client.feedback.store',$project_id,$comment_id),'enctype'=>"multipart/form-data")) }}
<div class="row">
    <input type="hidden" name="parent" value="{{$comment_id}}">
    <div class="form-group  col-md-12">
        {{ Form::label('feedback', __('Feedback')) }}
        {!! Form::textarea('feedback', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('file', __('File')) }}
        {{ Form::file('file', null, array('class' => 'form-control')) }}
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Post'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}


