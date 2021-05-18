{{ Form::open(array('url' => 'coupon','method' =>'post')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{Form::label('name',__('Name'))}}
        {{Form::text('name',null,array('class'=>'form-control font-style','required'=>'required'))}}
    </div>

    <div class="form-group col-md-6">
        {{Form::label('discount',__('Discount'))}}
        {{Form::number('discount',null,array('class'=>'form-control','required'=>'required','step'=>'0.01'))}}
        <span class="small">{{__('Note: Discount in Percentage')}}</span>
    </div>
    <div class="form-group col-md-6">
        {{Form::label('limit',__('Limit'))}}
        {{Form::number('limit',null,array('class'=>'form-control','required'=>'required'))}}
    </div>

    <div class="form-group col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="custom-control custom-radio mb-3">
                    <input type="radio" id="customRadio5" name="icon-input" value="manual" class="custom-control-input code" checked>
                    <label class="custom-control-label" for="customRadio5">{{__('Manual')}}</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom-control custom-radio mb-3">
                    <input type="radio" id="customRadio6" name="icon-input" value="auto" class="custom-control-input code">
                    <label class="custom-control-label" for="customRadio6">{{__('Auto Generate')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12 d-block" id="manual">
        <input class="form-control font-uppercase" name="manualCode" type="text">
    </div>
    <div class="form-group col-md-12 d-none" id="auto">
        <div class="row">
            <div class="col-md-10">
                <input class="form-control" name="autoCode" type="text" id="auto-code">
            </div>
            <div class="col-md-2">
                <a href="#" class="btn btn-primary" id="code-generate"><i class="fas fa-history"></i></a>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12 text-right">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
</div>
{{ Form::close() }}

