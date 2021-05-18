{{ Form::model($deal, array('route' => array('deal.users.update', $deal->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('User')) }}
    {{ Form::select('users[]', $users,false, array('class' => 'form-control custom-select','multiple'=>'')) }}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}

