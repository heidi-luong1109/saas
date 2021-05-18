{{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
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
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
</div>


{{ Form::close() }}
