{{ Form::model($contract, array('route' => array('contract.update', $contract->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('client', __('Client')) }}
        {{ Form::select('client', $clients,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('subject', __('Subject')) }}
        {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('type', __('Contract Type')) }}
        {{ Form::select('type', $contractTypes,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('value', __('Contract Value')) }}
        {{ Form::number('value', null, array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_date', __('End Date')) }}
        {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
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
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
