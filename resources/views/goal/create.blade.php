{{ Form::open(array('url' => 'goal')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('amount', __('Amount')) }}
        {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('goal_type', __('Type')) }}
        {{ Form::select('goal_type',$types,null, array('class' => 'form-control customer-sel font-style custom-select ','required'=>'required')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('from', __('From')) }}
        {{ Form::month('from','', array('class' => 'form-control')) }}
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('tp', __('To')) }}
        {{ Form::month('to','', array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-md-12">
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" name="display" id="display" checked>
            <label class="custom-control-label" for="display">{{__('Display On Dashboard')}}</label>
        </div>
    </div>
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}
