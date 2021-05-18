{{ Form::open(array('route' => array('project.user.add',$id))) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('user', __('User')) }}
        {!! Form::select('user[]', $employee, null,array('class' => 'form-control custom-select','required'=>'required')) !!}
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
    </div>
</div>

{{ Form::close() }}
