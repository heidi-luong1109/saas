{{Form::open(array('url'=>'complaint','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        @if(\Auth::user()->type !='employee')
            <div class="form-group col-md-6 col-lg-6 ">
                {{ Form::label('complaint_from', __('Complaint From')) }}
                {{ Form::select('complaint_from', $employees,null, array('class' => 'form-control  custom-select','required'=>'required')) }}
            </div>
        @endif
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('complaint_against',__('Complaint Against'))}}
            {{Form::select('complaint_against',$employees,null,array('class'=>'form-control custom-select'))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('title',__('Title'))}}
            {{Form::text('title',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('complaint_date',__('Complaint Date'))}}
            {{Form::date('complaint_date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-12">
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
