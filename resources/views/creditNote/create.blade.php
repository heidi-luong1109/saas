{{ Form::open(array('url' => 'creditNote')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('invoice', __('Invoice')) }}
        <select class="form-control customer-sel font-style custom-select" required="required" id="invoice" name="invoice">
            <option value="0">{{__('Select Invoice')}}</option>
            @foreach($invoices as $key=>$invoice)
                <option value="{{$key}}">{{\Auth::user()->invoiceNumberFormat($invoice)}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', null, array('class' => 'form-control','required'=>'required','id'=>'amount')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
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
