@if($invoice->type=='Product')
    {{ Form::open(array('route' => array('invoice.store.product',$invoice->id))) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('item', __('Item')) }}
            {{ Form::select('item', $items,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('quantity', __('Quantity')) }}
            {{ Form::number('quantity',null, array('class' => 'form-control quantity','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Price')) }}
            {{ Form::number('price',null, array('class' => 'form-control price','required'=>'required','stage'=>'0.01')) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('discount', __('Discount')) }}
            {{ Form::number('discount',null, array('class' => 'form-control discount')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('tax', __('Tax')) }}
            {{ Form::hidden('tax',null, array('class' => 'form-control taxId')) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="tax"></div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description')) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="col-md-12">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
            </div>
        </div>
    </div>

    {{ Form::close() }}

@else
    {{ Form::open(array('route' => array('invoice.store.project',$invoice->id))) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::text('project',(!empty($invoice->projects)?$invoice->projects->title:''), array('class' => 'form-control','readonly')) }}
        </div>
        <div class="form-group col-md-6">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input type" id="customRadio5" name="type" value="milestone" checked="checked">
                <label class="custom-control-label" for="customRadio5">{{__('Milestone & Task')}}</label>
            </div>
        </div>
        <div class="form-group col-md-6">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input type" id="customRadio6" name="type" value="other">
                <label class="custom-control-label" for="customRadio6">{{__('Other')}}</label>
            </div>
        </div>
        <div class="form-group col-md-6 milestoneTask">
            {{ Form::label('milestone', __('Milestone')) }}
            {{ Form::select('milestone', $milestons,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6 milestoneTask">
            {{ Form::label('task', __('Task')) }}
            {{ Form::select('task', $tasks,null, array('class' => 'form-control custom-select','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12 title d-none">
            {{ Form::label('title', __('Title')) }}
            {{ Form::text('title',null, array('class' => 'form-control discount')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Price')) }}
            {{ Form::number('price',null, array('class' => 'form-control discount')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('discount', __('Discount')) }}
            {{ Form::number('discount',null, array('class' => 'form-control discount')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('tax', __('Tax')) }}
            {{ Form::hidden('tax',$invoice->tax, array('class' => 'form-control taxId1')) }}
            <div class="row">
                @foreach($taxes as $tax)
                    <div class="col-md-2">
                        <div class="tax1"><h4><span class="badge badge-primary">{{$tax->name .' ('.$tax->rate.' %)'}}</span></h4></div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description')) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        </div>
        <div class="col-md-12">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endif




