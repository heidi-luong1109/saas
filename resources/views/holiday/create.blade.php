{{Form::open(array('url'=>'holiday','method'=>'post'))}}
<div class="card-body p-0">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('date',__('Date'))}}
            {{Form::date('date',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('occasion',__('Occasion'))}}
            {{Form::text('occasion',null,array('class'=>'form-control'))}}
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
