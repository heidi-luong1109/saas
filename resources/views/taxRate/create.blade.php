{{ Form::open(array('url' => 'taxRate')) }}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('rate', __('Rate')) }}
    {{ Form::number('rate', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
