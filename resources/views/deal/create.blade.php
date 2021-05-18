{{ Form::open(array('url' => 'deal')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Deal Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('price', __('Price')) }}
        {{ Form::number('price', 0, array('class' => 'form-control','min'=>0)) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('clients', __('Clients')) }}
        {{ Form::select('clients[]', $clients,null, array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
