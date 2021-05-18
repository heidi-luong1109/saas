{{ Form::model($lead, array('route' => array('lead.update', $lead->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('subject', __('Subject')) }}
        {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('user_id', __('Employee')) }}
        {{ Form::select('user_id', $employees,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('email', __('Email')) }}
        {{ Form::text('email', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('pipeline_id', __('Pipeline')) }}
        {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('stage_id', __('Stage')) }}
        {{ Form::select('stage_id', [''=>__('Select Stages')],null, array('class' => 'form-control custom-select','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('sources', __('Sources')) }}
        {{ Form::select('sources[]', $sources,null, array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('products', __('Items')) }}
        {{ Form::select('products[]', $products,explode(',',$lead->items), array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('notes', __('Notes')) }}
        {!! Form::textarea('notes', null, ['class'=>'form-control','rows'=>'2']) !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
<script>


    var stage_id = '{{$lead->stage_id}}';

    $(document).ready(function () {
        var pipeline_id = $('[name=pipeline_id]').val();
        getStages(pipeline_id);
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        var currVal = $(this).val();
        getStages(currVal);
    });

    function getStages(id) {
        $.ajax({
            url: '{{route('lead.json')}}',
            data: {pipeline_id: id, _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty().append('<option value="" selected="selected">{{__('Select Stages')}}</option>');
                if (stage_cnt > 0) {
                    $.each(data, function (key, data) {
                        var select = '';
                        if (key == '{{ $lead->stage_id }}') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data + '</option>');
                    });
                }
                $("#stage_id").val(stage_id);

            }
        })
    }
</script>
