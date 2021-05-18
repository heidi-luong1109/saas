{{ Form::model($expense,array('route' => array('expense.update',$expense->id),'method'=>'PUT','enctype' => "multipart/form-data")) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('date', __('Date')) }}
        {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('user', __('User')) }}
        {{ Form::select('user', $users,null, array('class' => 'form-control custom-select')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('project', __('Project')) }}
        {{ Form::select('project', $projects,null, array('class' => 'form-control custom-select')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('attachment', __('Attachment')) }}
        {{ Form::file('attachment', array('class' => 'form-control','accept'=>'.jpeg,.jpg,.png,.doc,.pdf')) }}
    </div>
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description')) }}
        {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>'2')) }}
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}
