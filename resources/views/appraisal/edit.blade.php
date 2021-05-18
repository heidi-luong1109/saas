{{Form::model($appraisal,array('route' => array('appraisal.update', $appraisal->id), 'method' => 'PUT')) }}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-6">
            {{Form::label('employee',__('Employee'))}}
            {{Form::select('employee',$employees,null,array('class'=>'form-control custom-select','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('appraisal_date',__('Select Month'))}}
            {{ Form::month('appraisal_date',null, array('class' => 'form-control')) }}
        </div>
        <div class="col-md-12">
            <h4>{{__('Technical Competencies')}}</h4>
            <hr class="my-3">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('customer_experience',__('Customer Experience'))}}
                {{Form::select('customer_experience',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('marketing',__('Marketing'))}}
                {{Form::select('marketing',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('administration',__('Administration'))}}
                {{Form::select('administration',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>{{__('Organizational Competencies')}}</h4>
            <hr class="my-3">
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('professionalism',__('Professionalism'))}}
                {{Form::select('professionalism',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('integrity',__('Integrity'))}}
                {{Form::select('integrity',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('attendance',__('Attendance'))}}
                {{Form::select('attendance',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('remark',__('Remarks'))}}
                {{Form::textarea('remark',null,array('class'=>'form-control','rows'=>3))}}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}



