{{ Form::model($lead, array('route' => array('lead.items.update', $lead->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('Items')) }}
    {{ Form::select('items[]', $products,false, array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}

