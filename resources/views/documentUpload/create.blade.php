{{Form::open(array('url'=>'document-upload','method'=>'post', 'enctype' => "multipart/form-data"))}}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'))}}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('document',__('Document'))}}
            {{Form::file('document',array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description')) }}
            {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>3)) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
