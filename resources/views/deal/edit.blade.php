{{ Form::model($deal, array('route' => array('deal.update', $deal->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('name', __('Deal Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('price', __('Price')) }}
        {{ Form::number('price',null, array('class' => 'form-control','min'=>0)) }}
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
        {{ Form::select('products[]', $products,null, array('class' => 'form-control custom-select','multiple'=>'','required'=>'required')) }}
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('notes', __('Notes')) }}
        {{ Form::textarea('notes',null, array('class' => 'form-control','rows'=>'2')) }}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}

<script>


    var stage_id = '{{$deal->stage_id}}';

    $(document).ready(function () {
        $("#commonModal select[name=pipeline_id]").trigger('change');
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        $.ajax({
            url: '{{route('dealStage.json')}}',
            data: {pipeline_id: $(this).val(), _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                $("#stage_id").html('<option value="" selected="selected">{{__('Select Deal Stages')}}</option>');
                $.each(data, function (key, data) {
                    var select = '';
                    if (key == '{{ $deal->stage_id }}') {
                        select = 'selected';
                    }

                    $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data + '</option>');
                });
                $("#stage_id").val(stage_id);
            }
        })
    });
</script>
