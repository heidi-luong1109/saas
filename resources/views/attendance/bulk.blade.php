@extends('layouts.admin')
@section('page-title')
    {{__('Attendance')}}
@endsection
@push('script-page')
    <script>
        $('#present_all').click(function (event) {
            if (this.checked) {
                $('.present').each(function () {
                    this.checked = true;
                });

                $('.present_check_in').removeClass('d-none');
                $('.present_check_in').addClass('d-block');

            } else {
                $('.present').each(function () {
                    this.checked = false;
                });
                $('.present_check_in').removeClass('d-block');
                $('.present_check_in').addClass('d-none');

            }
        });

        $('.present').click(function (event) {
            var div = $(this).parent().parent().parent().parent().find('.present_check_in');
            if (this.checked) {
                div.removeClass('d-none');
                div.addClass('d-block');

            } else {
                div.removeClass('d-block');
                div.addClass('d-none');
            }

        });


    </script>
    <script>
        $(document).ready(function () {
            $('.daterangepicker').daterangepicker({
                format: 'yyyy-mm-dd',
                locale: {format: 'YYYY-MM-DD'},
            });
        });
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Attendance')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Attendance')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('bulk.attendance'),'method'=>'get')) }}
                        <div class="row mb-4">
                            <div class="col">
                                <h4 class="h4 mb-0"></h4>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('date',__('Date'))}}
                                {{Form::date('date',isset($_GET['date'])?$_GET['date']:date('Y-m-d'),array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('department', __('Department')) }}
                                {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control custom-select','required')) }}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="">
                            <thead class="thead-light">
                            <tr>
                                <th width="10%">{{__('Employee Id')}}</th>
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="present_all" id="present_all" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="present_all"> {{__('Attendance')}}</label>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            </thead>

                            {{Form::open(array('route'=>array('bulk.attendance'),'method'=>'post'))}}
                            <tbody>
                            <input type="hidden" value="{{isset($_GET['date'])?$_GET['date']:date('Y-m-d')}}" name="date">
                            <input type="hidden" value="{{isset($_GET['branch'])?$_GET['branch']:''}}" name="branch">
                            <input type="hidden" value="{{isset($_GET['department'])?$_GET['department']:''}}" name="department">
                            @forelse($employees as $employee)
                                @php

                                    $attendance=$employee->present_status($employee->user_id,isset($_GET['date'])?$_GET['date']:date('Y-m-d'));

                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" value="{{$employee->user_id}}" name="employee_id[]">
                                      {{ \Auth::user()->employeeIdFormat($employee->employee_id) }}
                                    </td>
                                    <td>{{!empty($employee->users)?$employee->users->name:''}}</td>
                                    <td>{{!empty($employee->departments)?$employee->departments->name:''}}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input present" type="checkbox" name="present-{{$employee->user_id}}" id="present{{$employee->user_id}}" {{ (!empty($attendance)&&$attendance->status == 'Present') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="present{{$employee->user_id}}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 present_check_in {{ empty($attendance) ? 'd-none' : '' }} ">
                                                <div class="row">
                                                    <label class="col-md-2 control-label">{{__('In')}}</label>
                                                    <div class="col-md-4">
                                                        <input type="time" class="form-control" name="in-{{$employee->user_id}}" value="{{!empty($attendance) && $attendance->clock_in!='00:00:00' ? $attendance->clock_in : \Utility::getValByName('company_start_time')}}">
                                                    </div>

                                                    <label for="inputValue" class="col-md-2 control-label">{{__('Out')}}</label>
                                                    <div class="col-md-4">
                                                        <input type="time" class="form-control" name="out-{{$employee->user_id}}" value="{{!empty($attendance) &&  $attendance->clock_out !='00:00:00'? $attendance->clock_out : \Utility::getValByName('company_end_time')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{__('No data available in table')}}</td>
                                </tr>
                            @endforelse

                            </tbody>
                            <div class="attendance-btn">
                                {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
                            </div>
                            {{Form::close()}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

