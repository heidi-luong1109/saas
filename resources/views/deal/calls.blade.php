
@if(isset($call))
    {{ Form::model($call, array('route' => array('deal.call.update', $deal->id, $call->id), 'method' => 'PUT')) }}
@else
    {{ Form::open(array('route' => ['deal.call.store',$deal->id])) }}
@endif

<div class="form-group">
    {{ Form::label('subject', __('Subject')) }}
    {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
</div>
<div class="form-group">
    {{ Form::label('call_type', __('Call Type')) }}
    <select name="call_type" id="call_type" class="form-control custom-select" required>
        <option value="outbound" @if(isset($call->call_type) && $call->call_type == 'outbound') selected @endif>{{__('Outbound')}}</option>
        <option value="inbound" @if(isset($call->call_type) && $call->call_type == 'inbound') selected @endif>{{__('Inbound')}}</option>
    </select>
</div>
<div class="form-group">
    {{ Form::label('duration', __('Duration')) }} <small class="font-weight-bold">{{ __(' (Format h:m:s i.e 00:35:20 means 35 Minutes and 20 Sec)') }}</small>
    {{ Form::time('duration', null, array('class' => 'form-control','placeholder'=>'00:35:20')) }}
</div>
<div class="form-group">
    {{ Form::label('user_id', __('Assignee')) }}
    <select name="user_id" id="user_id" class="form-control custom-select" required>
        @foreach($users as $user)
            <option value="{{ $user->getDealUser->id }}" @if(isset($call->user_id) && $call->user_id == $user->getDealUser->id) selected @endif>{{ $user->getDealUser->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {{ Form::label('description', __('Description')) }}
    {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>'2')) }}
</div>
<div class="form-group">
    {{ Form::label('call_result', __('Call Result')) }}
    {{ Form::textarea('call_result', null, array('class' => 'form-control','rows'=>'2')) }}
</div>

@if(isset($call))
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
    </div>
@else
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
    </div>
@endif
{{ Form::close() }}
