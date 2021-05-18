{{ Form::open(array('route' => array('invoice.store.receipt',$invoice->id))) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount',$invoice->getDue(), array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::date('date',null, array('class' => 'form-control quantity','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('payment_method', __('Payment Method')) }}
        {{ Form::select('payment_method', $paymentMethods,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
    <div class="col-md-12">
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
            {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
        </div>
    </div>
</div>

{{ Form::close() }}
