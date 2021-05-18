{{Form::model($award,array('route' => array('award.update', $award->id), 'method' => 'PUT')) }}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-6 col-lg-6 ">
            {{ Form::label('employee_id', __('Employee')) }}
            {{ Form::select('employee_id', $employees,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('award_type', __('Award Type')) }}
            {{ Form::select('award_type', $awardtypes,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('date',__('Date'))}}
            {{Form::date('date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('gift',__('Gift'))}}
            {{Form::text('gift',null,array('class'=>'form-control','placeholder'=>__('Enter Gift')))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('description',__('Description'))}}
            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Description')))}}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}

