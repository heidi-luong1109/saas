{{ Form::open(array('route' => ['lead.label.store',$lead->id])) }}
<div class="form-group">
    <div class="row gutters-xs">
        @foreach ($labels as $label)
            <div class="col-12 custom-control custom-checkbox mt-2 mb-2">
                {{ Form::checkbox('labels[]',$label->id,(array_key_exists($label->id,$selected))?true:false,['class' => 'custom-control-input','id'=>'labels_'.$label->id]) }}
                {{ Form::label('labels_'.$label->id, ucfirst($label->name),['class'=>'custom-control-label ml-4 badge badge-'.$label->color]) }}
            </div>
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
