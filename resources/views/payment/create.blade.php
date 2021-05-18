{{ Form::open(array('url' => 'payment')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::date('date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('payment_method', __('Payment Method')) }}
        {{ Form::select('payment_method', $paymentMethod,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('client', __('Client')) }}
        {{ Form::select('client', $clients,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('reference', __('Reference')) }}
        {{ Form::text('reference', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>'2')) }}
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}
