{{ Form::open(array('url' => 'account-assets')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('purchase_date', __('Purchase Date')) }}
        {{ Form::date('purchase_date','', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('supported_date', __('Support Until')) }}
        {{ Form::date('supported_date','', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>'3')) }}
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}

