{{ Form::model($lead, array('route' => array('lead.sources.update', $lead->id), 'method' => 'PUT')) }}
<div class="form-group">
    <div class="row gutters-xs">
        @foreach ($sources as $source)
            <div class="col-12 custom-control custom-checkbox mt-2 mb-2">
                {{ Form::checkbox('sources[]',$source->id,($selected && array_key_exists($source->id,$selected))?true:false,['class' => 'custom-control-input','id'=>'sources_'.$source->id]) }}
                {{ Form::label('sources_'.$source->id, ucfirst($source->name),['class'=>'custom-control-label ml-4 badge badge-'.$source->color]) }}
            </div>
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
