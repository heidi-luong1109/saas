{{ Form::model($noticeBoard,array('route' => array('noticeBoard.update',$noticeBoard),'method'=>'PUT')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('heading', __('Notice Heading')) }}
        {{ Form::text('heading', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        <div class="form-group">
            <label class="d-block"></label>
            <div class="row">
                <div class="col-md-4">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input type" id="customRadio5" name="type" value="Client" {{$noticeBoard->type=='Client'?'checked':''}}>
                        <label class="custom-control-label" for="customRadio5">{{__('To Clients')}}</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input type" id="customRadio6" name="type" value="Employee" {{$noticeBoard->type=='Employee'?'checked':''}}>
                        <label class="custom-control-label" for="customRadio6">{{__('To Employees')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group col-md-12 department {{$noticeBoard->type=='Employee'?'d-block':'d-none'}}">
        {{ Form::label('department', __('Department')) }}
        {{ Form::select('department', $departments,null, array('class' => 'form-control custom-select')) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('notice_detail', __('Notice Details')) }}
        {!! Form::textarea('notice_detail', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
