{{ Form::model($lead, array('route' => array('lead.convert.to.deal', $lead->id), 'method' => 'POST')) }}
<div class="row">
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('name', __('Deal Name')) }}
            {{ Form::text('name', $lead->subject, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{ Form::label('price', __('Price')) }}
            {{ Form::number('price', 0, array('class' => 'form-control','min'=>0)) }}
        </div>
    </div>
    <div class="col-sm-12 col-md-12 mb-10">
        <div id="custom-control-inline-component" class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="custom-control-inline-component-tab">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline1" name="client_check" value="new" class="custom-control-input" @if(empty($exist_client)) checked @endif>
                <label class="custom-control-label" for="customRadioInline1">{{__('New Client')}}</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline2" name="client_check" value="exist" class="custom-control-input" @if(empty($exist_client)) checked @endif>
                <label class="custom-control-label" for="customRadioInline2">{{__('Existing Client')}}</label>
            </div>
        </div>
    </div>
    <div class="col-6 exist_client d-none">
        <div class="form-group">
            {{ Form::label('clients', __('Client')) }}
            <select name="clients" id="clients" class="form-control custom-select">
                <option value="">{{ __('Select Client') }}</option>
                @foreach($clients as $client)
                    <option value="{{ $client->email }}" @if($lead->email == $client->email) selected @endif>{{ $client->name }} ({{ $client->email }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-6 new_client">
        <div class="form-group">
            {{ Form::label('client_name', __('Client Name')) }}
            {{ Form::text('client_name', $lead->name, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
    <div class="col-6 new_client">
        <div class="form-group">
            {{ Form::label('client_email', __('Client Email')) }}
            {{ Form::text('client_email', $lead->email, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
    <div class="col-6 new_client">
        <div class="form-group">
            {{ Form::label('client_password', __('Client Password')) }}
            {{ Form::text('client_password',null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="row px-3 pt-2">
    <div class="col-12 pl-0 pb-2">
        <b>Copy To</b>
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','products',false,['class' => 'custom-control-input','id'=>'is_transfer_products','checked'=>'checked']) }}
        {{ Form::label('is_transfer_products', __('Products'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','sources',false,['class' => 'custom-control-input','id'=>'is_transfer_sources','checked'=>'checked']) }}
        {{ Form::label('is_transfer_sources', __('Sources'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','files',false,['class' => 'custom-control-input','id'=>'is_transfer_files','checked'=>'checked']) }}
        {{ Form::label('is_transfer_files', __('Files'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','discussion',false,['class' => 'custom-control-input','id'=>'is_transfer_discussion','checked'=>'checked']) }}
        {{ Form::label('is_transfer_discussion', __('Discussion'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','notes',false,['class' => 'custom-control-input','id'=>'is_transfer_notes','checked'=>'checked']) }}
        {{ Form::label('is_transfer_notes', __('Notes'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','calls',false,['class' => 'custom-control-input','id'=>'is_transfer_calls','checked'=>'checked']) }}
        {{ Form::label('is_transfer_calls', __('Calls'),['class'=>'custom-control-label']) }}
    </div>
    <div class="col-3 custom-control custom-checkbox">
        {{ Form::checkbox('is_transfer[]','emails',false,['class' => 'custom-control-input','id'=>'is_transfer_emails','checked'=>'checked']) }}
        {{ Form::label('is_transfer_emails', __('Emails'),['class'=>'custom-control-label']) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Convert'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}


<script>


    $(document).ready(function () {
        var is_client = $("input[name='client_check']:checked").val();


        $("input[name='client_check']").click(function () {
            is_client = $(this).val();
            console.log(is_client);
            if (is_client == "exist") {
                $('.exist_client').removeClass('d-none');
                $('#client_name').removeAttr('required');
                $('#client_email').removeAttr('required');
                $('#client_password').removeAttr('required');
                $('.new_client').addClass('d-none');
            } else {
                $('.new_client').removeClass('d-none');
                $('#client_name').attr('required', 'required');
                $('#client_email').attr('required', 'required');
                $('#client_password').attr('required', 'required');
                $('.exist_client').addClass('d-none');
            }
        });
        if (is_client == "exist") {
            $('.exist_client').removeClass('d-none');
            $('#client_name').removeAttr('required');
            $('#client_email').removeAttr('required');
            $('#client_password').removeAttr('required');
            $('.new_client').addClass('d-none');
        } else {
            $('.new_client').removeClass('d-none');
            $('#client_name').attr('required', 'required');
            $('#client_email').attr('required', 'required');
            $('#client_password').attr('required', 'required');
            $('.exist_client').addClass('d-none');
        }
    })

</script>
