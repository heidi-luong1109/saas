{{ Form::open(array('url' => 'employee')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('email', __('Email')) }}
        {{ Form::text('email', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('password', __('Password')) }}
        {{Form::password('password',array('class'=>'form-control','required'=>'required','minlength'=>"6"))}}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
