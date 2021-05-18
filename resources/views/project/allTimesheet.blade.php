@extends('layouts.admin')
@push('css-page')
@endpush
@push('script-page')

    <script>

        $(document).on('change', 'select[name=project]', function () {
            var project_id = $(this).val();
            getTask(project_id);
            getUser(project_id);
        });
        $(document).on('change', '#project_id', function () {
            var project_id = $(this).val();
            getProjectTask(project_id);
            getProjectUser(project_id);
        });

        function getTask(project_id) {
            $.ajax({
                url: '{{route('project.getTask')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#task').empty();
                    $('#task').append('<option value="">{{__('All')}}</option>');
                    $.each(data, function (key, value) {
                        $('#task').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        function getUser(project_id) {
            $.ajax({
                url: '{{route('project.getUser')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {

                    $('#user').empty();
                    $('#user').append('<option value="">{{__('All')}}</option>');
                    $.each(data, function (key, value) {

                        $('#user').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        function getProjectTask(project_id) {
            $.ajax({
                url: '{{route('project.getTask')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#task_id').empty();
                    $('#task_id').append('<option value="">--</option>');
                    $.each(data, function (key, value) {
                        $('#task_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        function getProjectUser(project_id) {
            $.ajax({
                url: '{{route('project.getUser')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#users').empty();
                    $.each(data, function (key, value) {
                        $('#users').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        $("select[name=project]").trigger("change");
    </script>
@endpush
@section('page-title')
    {{__('Timesheet')}}
@endsection
@push('css-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Manage Timesheet')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'Timesheet'}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                {{ Form::open(array('route' => array('project.all.timesheet'),'method'=>'get')) }}
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('project', __('Project')) }}
                                            {{ Form::select('project', $projectList,!empty($_GET['project'])?$_GET['project']:'', array('class' => 'form-control custom-select')) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('task', __('Task')) }}
                                            <select class="form-control custom-select" name="task" id="task">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('user', __('User')) }}
                                            <select class="form-control custom-select" name="user" id="user">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        {{Form::label('start_date',__('Start Date'))}}
                                        {{Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'form-control'))}}
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::label('end_date',__('End Date'))}}
                                        {{Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'form-control'))}}
                                    </div>
                                    <div class="col-auto apply-btn">
                                        {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                        <a href="{{route('project.all.timesheet')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body1 notes-page">
                        <div class="tab-content">
                            <div id="taskList" class="tab-pane fade in active show">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <!-- Card header -->
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Timesheet')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('project.timesheet.create',0) }}" data-ajax-popup="true" data-title="{{__('Create New Timesheet')}}" class="btn btn-outline-primary btn-sm">
                                                                   <i class="fa fa-plus"></i>  {{__('Create')}}
                                                               </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Project')}}</th>
                                                        <th>{{__('Task')}}</th>
                                                        <th>{{__('User')}}</th>
                                                        <th>{{__('Start Date')}}</th>
                                                        <th>{{__('Start Time')}}</th>
                                                        <th>{{__('End Date')}}</th>
                                                        <th>{{__('End Time')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($timesheet as $log)
                                                        <tr>
                                                            <td> {{!empty($log->projects)?$log->projects->title:''}}</td>
                                                            <td>{{!empty($log->tasks)?$log->tasks->title:'-'}}</td>
                                                            <td> {{!empty($log->users)?$log->users->name:''}}</td>
                                                            <td> {{\Auth::user()->dateFormat($log->start_date)}}</td>
                                                            <td> {{\Auth::user()->timeFormat($log->start_time)}}</td>
                                                            <td> {{\Auth::user()->dateFormat($log->end_date)}}</td>
                                                            <td> {{\Auth::user()->timeFormat($log->end_time)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


