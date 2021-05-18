{{ Form::open(array('url' => 'lead')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('subject', __('Subject')) }}
        {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('user_id', __('Employee')) }}
        {{ Form::select('user_id', $employees,'', array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('email', __('Email')) }}
        {{ Form::text('email', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
