{{ Form::model($deal, array('route' => array('deal.items.update', $deal->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('items', __('Items')) }}
    {{ Form::select('items[]', $products,false, array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}

