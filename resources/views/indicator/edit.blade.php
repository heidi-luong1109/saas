{{Form::model($indicator,array('route' => array('indicator.update', $indicator->id), 'method' => 'PUT')) }}
<div class="card-body p-0">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('department',__('Department'))}}
                {{Form::select('department',$departments,null,array('class'=>'form-control custom-select','required'=>'required','id'=>'department_id'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('designation',__('Designation'))}}
                <select class="custom-select form-control select2-multiple" id="designation_id" name="designation" data-toggle="select2" data-placeholder="{{ __('Select Designation ...') }}" required>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <h4>{{__('Technical Competencies')}}</h4>
            <hr class="my-3">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('customer_experience',__('Customer Experience'))}}
                {{Form::select('customer_experience',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('marketing',__('Marketing'))}}
                {{Form::select('marketing',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('administration',__('Administration'))}}
                {{Form::select('administration',$technical,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4>{{__('Organizational Competencies')}}</h4>
            <hr class="my-3">
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('professionalism',__('Professionalism'))}}
                {{Form::select('professionalism',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('integrity',__('Integrity'))}}
                {{Form::select('integrity',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{Form::label('attendance',__('Attendance'))}}
                {{Form::select('attendance',$organizational,null,array('class'=>'form-control custom-select'))}}
            </div>
        </div>
    </div>

</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
<script type="text/javascript">
    function getDesignation(did) {
        $.ajax({
            url: '{{route('employee.json')}}',
            type: 'POST',
            data: {
                "department_id": did, "_token": "{{ csrf_token() }}",
            },
            success: function (data) {
                console.log(data);
                $('#designation_id').empty();
                $('#designation_id').append('<option value="">Select any Designation</option>');
                $.each(data, function (key, value) {
                    var select = '';
                    if (key == '{{ $indicator->designation }}') {
                        select = 'selected';
                    }

                    $('#designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                });
            }
        });
    }

    $(document).ready(function () {
        var d_id = $('#department_id').val();
        getDesignation(d_id);
    });

</script>



