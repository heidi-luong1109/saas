{{ Form::open(array('url' => 'invoice')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('issue_date', __('Issue Date')) }}
        {{ Form::date('issue_date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('due_date', __('Due Date')) }}
        {{ Form::date('due_date', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        <div class="form-group">
            <label class="d-block">{{__('Type')}}</label>
            <div class="row">
                <div class="col-md-6">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input type" id="customRadio5" name="type" value="Product" checked="checked">
                        <label class="custom-control-label" for="customRadio5">{{__('Product')}}</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input type" id="customRadio6" name="type" value="Project">
                        <label class="custom-control-label" for="customRadio6">{{__('Project')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('client', __('Client')) }}
        {{ Form::select('client', $clients,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>

    <div class="form-group col-md-6 project-field d-none">
        {{ Form::label('project', __('Project')) }}
        <select class="form-control custom-select user" name="project" id="project">

        </select>
    </div>
    <div class="form-group col-md-6 project-field d-none">
        {{ Form::label('tax', __('Tax')) }}
        {{ Form::select('tax[]', $taxes,null, array('class' => 'form-control custom-select','multiple')) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
