{{Form::open(array('url'=>'termination','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('employee_id', __('Employee')) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('termination_type', __('Termination Type')) }}
            {{ Form::select('termination_type', $terminationtypes,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('notice_date',__('Notice Date'))}}
            {{Form::date('notice_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('termination_date',__('Termination Date'))}}
            {{Form::date('termination_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group  col-lg-12">
            {{Form::label('description',__('Description'))}}
            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Description')))}}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
