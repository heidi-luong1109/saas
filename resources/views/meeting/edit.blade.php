{{ Form::model($meeting, array('route' => array('meeting.update', $meeting->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('department', __('Department')) }}
        {{ Form::select('department', $departments,null, array('class' => 'form-control custom-select')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('designation', __('Designation')) }}
        {{ Form::select('designation', $designations,null, array('class' => 'form-control custom-select')) }}
    </div>

    <div class="form-group col-md-12">
        {{Form::label('title',__('Title'))}}
        {{Form::text('title',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('date',__('Date'))}}
        {{Form::date('date',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('time',__('Time'))}}
        {{Form::time('time',null,array('class'=>'form-control'))}}
    </div>
    <div class="form-group col-md-12">
        {{Form::label('notes',__('Notes'))}}
        {{Form::textarea('notes',null,array('class'=>'form-control','rows'=>'2'))}}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
