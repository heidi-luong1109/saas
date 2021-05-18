{{ Form::model($label, array('route' => array('label.update', $label->id), 'method' => 'PUT')) }}
<div class="form-group">
    {{ Form::label('name', __('Label Name')) }}
    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('name', __('Pipeline')) }}
    {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control custom-select','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('name', __('Color')) }}
    <div class="row gutters-xs">
        @foreach($colors as $color)
            <div class="col-auto">
                <label class="colorinput">
                    <input name="color" type="radio" value="{{$color}}" class="colorinput-input">
                    <span class="colorinput-color bg-{{$color}}"></span>
                </label>
            </div>
        @endforeach
    </div>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}

