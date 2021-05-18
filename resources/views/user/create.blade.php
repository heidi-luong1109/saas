{{Form::open(array('url'=>'user','method'=>'post'))}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter User Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('email',__('Email'))}}
            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email'),'required'=>'required'))}}

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('password',__('Password'))}}
            {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}

        </div>
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>

{{Form::close()}}

