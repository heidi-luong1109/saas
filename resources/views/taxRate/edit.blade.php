{{ Form::model($taxRate, array('route' => array('taxRate.update', $taxRate->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('Name')) }}
    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('rate', __('Rate')) }}
    {{ Form::number('rate', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
