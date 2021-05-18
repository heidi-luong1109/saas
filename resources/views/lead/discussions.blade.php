{{ Form::model($lead, array('route' => array('lead.discussion.store', $lead->id), 'method' => 'POST')) }}

<div class="form-group">
    {{ Form::label('comment', __('Message')) }}
    {{ Form::textarea('comment', null, array('class' => 'form-control')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
