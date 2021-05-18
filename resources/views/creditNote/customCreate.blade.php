{{ Form::open(array('url' => 'creditNote')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('invoice', __('Invoice')) }}
        {{ Form::select('invoice', $invoices,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::text('date', '', array('class' => 'form-control datepicker','required'=>'required')) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
