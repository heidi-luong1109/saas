{{Form::open(array('url'=>'promotion','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('employee_id', __('Employee')) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('designation_id',__('Designation'))}}
            {{Form::select('designation_id',$designations,null,array('class'=>'form-control custom-select'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('promotion_title',__('Promotion Title'))}}
            {{Form::text('promotion_title',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{Form::label('promotion_date',__('Promotion Date'))}}
            {{Form::date('promotion_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-lg-12">
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
