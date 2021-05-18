{{ Form::open(array('url' => 'support','enctype'=>"multipart/form-data")) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('subject', __('Subject')) }}
        {{ Form::text('subject', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('user',__('Support for User'))}}
        {{Form::select('user',$users,null,array('class'=>'form-control custom-select'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('priority',__('Priority'))}}
        {{Form::select('priority',$priority,null,array('class'=>'form-control custom-select'))}}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('attachment', __('Attachment')) }}
        {{ Form::file('attachment', array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_date', __('End Date')) }}
        {{ Form::date('end_date', '', array('class' => 'form-control','required'=>'required')) }}
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
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
