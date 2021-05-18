{{ Form::open(array('route' => ['lead.email.store',$lead->id])) }}
<div class="form-group">
    {{ Form::label('to', __('Mail To')) }}
    {{ Form::email('to', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('subject', __('Subject')) }}
    {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('description', __('Description')) }}
    {{ Form::textarea('description', null, array('class' => 'form-control')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
