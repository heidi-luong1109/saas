{{ Form::open(array('url' => 'plan', 'enctype' => "multipart/form-data")) }}
<div class="row">
    <div class="form-group col-md-6">
        {{Form::label('name',__('Name'))}}
        {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
    </div>
    <div class="form-group col-md-6">
        {{Form::label('price',__('Price'))}}
        {{Form::number('price',null,array('class'=>'form-control','placeholder'=>__('Enter Plan Price'),'step'=>'0.01'))}}
    </div>

    <div class="form-group col-md-6">
        {{Form::label('max_employee',__('Maximum Employee'))}}
        {{Form::number('max_employee',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('max_client',__('Maximum Client'))}}
        {{Form::number('max_client',null,array('class'=>'form-control','required'=>'required'))}}
        <span class="small">{{__('Note: "-1" for Unlimited')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('duration', __('Duration')) }}
        {!! Form::select('duration', $arrDuration, null,array('class' => 'form-control custom-select','required'=>'required')) !!}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('image', __('Image')) }}
        {{ Form::file('image', array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'4']) !!}
    </div>
    <div class="form-group col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}

