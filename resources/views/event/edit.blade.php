{{ Form::model($event,array('route' => array('event.update',$event),'method'=>'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Event title')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('where', __('Where')) }}
        {{ Form::text('where', null, array('class' => 'form-control','required'=>'required')) }}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('start_date', __('Start Date')) }}
        {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('start_time', __('Start Time')) }}
        {{ Form::time('start_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_date', __('End Date')) }}
        {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('end_time', __('End Time')) }}
        {{ Form::time('end_time', null, array('class' => 'form-control','required'=>'required')) }}
    </div>

    <div class="form-group col-md-12">
        <label class="form-control-label d-block mb-3">{{__('Status color')}}</label>
        <div class="btn-group btn-group-toggle btn-group-colors event-tag mb-0" data-toggle="buttons">
            <label class="btn bg-info mr-2 {{($event->color=='bg-info')?'active':''}}">
                <input type="radio" name="color" value="bg-info" autocomplete="off">
            </label>
            <label class="btn bg-warning mr-2 {{($event->color=='bg-warning')?'active':''}}">
                <input type="radio" name="color" value="bg-warning" autocomplete="off">
            </label>
            <label class="btn bg-danger mr-2 {{($event->color=='bg-danger')?'active':''}}">
                <input type="radio" name="color" value="bg-danger" autocomplete="off">
            </label>
            <label class="btn bg-success mr-2 {{($event->color=='bg-success')?'active':''}}">
                <input type="radio" name="color" value="bg-success" autocomplete="off">
            </label>
            <label class="btn bg-default mr-2 {{($event->color=='bg-default')?'active':''}}">
                <input type="radio" name="color" value="bg-default" autocomplete="off">
            </label>
            <label class="btn bg-primary mr-2 {{($event->color=='bg-primary')?'active':''}}">
                <input type="radio" name="color" value="bg-primary" autocomplete="off">
            </label>
        </div>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
    </div>
    <div class="col-md-12">
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
            {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
        </div>
    </div>
</div>
{{ Form::close() }}


