{{Form::model($trainer,array('route' => array('trainer.update', $trainer->id), 'method' => 'PUT')) }}
<div class="card-body p-0">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('firstname',__('First Name'))}}
                {{Form::text('firstname',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('lastname',__('Last Name'))}}
                {{Form::text('lastname',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('contact',__('Contact'))}}
                {{Form::text('contact',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('email',__('Email'))}}
                {{Form::text('email',null,array('class'=>'form-control','required'=>'required'))}}
            </div>
        </div>
        <div class="form-group col-lg-12">
            {{Form::label('expertise',__('Expertise'))}}
            {{Form::textarea('expertise',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Expertise')))}}
        </div>
        <div class="form-group col-lg-12">
            {{Form::label('address',__('Address'))}}
            {{Form::textarea('address',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Address')))}}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}




