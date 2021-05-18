{{ Form::model($deal, array('route' => array('deal.clients.update', $deal->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('clients', __('Clients')) }}
    {{ Form::select('clients[]', $clients,false, array('class' => 'form-control custom-select','multiple'=>'')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}

