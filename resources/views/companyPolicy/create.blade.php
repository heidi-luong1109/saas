{{Form::open(array('url'=>'company-policy','method'=>'post', 'enctype' => "multipart/form-data"))}}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('title',__('Title'))}}
            {{Form::text('title',null,array('class'=>'form-control','required'=>'required'))}}
        </div>

        <div class="form-group col-md-12">
            {{Form::label('attachment',__('Attachment'))}}
            {{Form::file('attachment',array('class'=>'form-control'))}}
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
