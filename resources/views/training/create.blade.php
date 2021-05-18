{{Form::open(array('url'=>'training','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('trainer_option',__('Trainer Option'))}}
                {{Form::select('trainer_option',$options,null,array('class'=>'form-control custom-select','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('training_type',__('Training Type'))}}
                {{Form::select('training_type',$trainingTypes,null,array('class'=>'form-control custom-select','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('trainer',__('Trainer'))}}
                {{Form::select('trainer',$trainers,null,array('class'=>'form-control custom-select','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('training_cost',__('Training Cost'))}}
                {{Form::number('training_cost',null,array('class'=>'form-control','step'=>'0.01','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('employee',__('Employee'))}}
                {{Form::select('employee',$employees,null,array('class'=>'form-control custom-select','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('start_date',__('Start Date'))}}
                {{Form::date('start_date',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('end_date',__('End Date'))}}
                {{Form::date('end_date',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="form-group col-lg-12">
            {{Form::label('description',__('Description'))}}
            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Description')))}}
        </div>

    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
