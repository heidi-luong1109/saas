@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@push('css-page')
@endpush
@push('script-page')
    <script src="{{asset('assets/module/js/dragula.min.js')}}"></script>
    <script>
        !function (a) {

            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {

                a('[data-plugin="dragula"]').each(function () {

                    var t = a(this).data("containers"), n = [];

                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');
                        var old_status = $("#" + source.id).attr('data-status');
                        var new_status = $("#" + target.id).attr('data-status');
                        var stage_id = $(target).attr('data-id');

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);

                        $.ajax({
                            url: '{{route('project.task.order')}}',
                            type: 'POST',
                            data: {task_id: id, stage_id: stage_id, order: order, old_status: old_status, new_status: new_status, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                toastrs('Success', 'task successfully updated', 'success');
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                toastrs('Error', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
    <script>
        $(document).on("change", "#change-project-status select[name=status]", function () {
            $('#change-project-status').submit();
        });
    </script>
    <script>
        $(document).on('click', '.form-checklist', function (e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-checklist").data('action'),
                type: 'POST',
                data: $('#form-checklist').serialize(),
                dataType: 'JSON',
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist successfully created.")}}', 'success');

                    var html = '<li class="media">' +
                        '<div class="media-body">' +
                        '<h5 class="mt-0 mb-1 font-weight-bold"> </h5> ' +
                        '<div class=" custom-control custom-checkbox checklist-checkbox"> ' +
                        '<input type="checkbox" id="checklist-' + data.id + '" class="custom-control-input"  data-url="' + data.updateUrl + '">' +
                        '<label for="checklist-' + data.id + '" class="custom-control-label"></label> ' +
                        '' + data.name + ' </div>' +
                        '<div class="comment-trash" style="float: right"> ' +
                        '<a href="#" class="btn btn-outline btn-sm red text-muted delete-checklist" data-url="' + data.deleteUrl + '">\n' +
                        '                                                            <i class="far fa-trash-alt"></i>' +
                        '</a>' +
                        '</div>' +
                        '</div>' +
                        ' </li>';


                    $("#check-list").prepend(html);
                    $("#form-checklist input[name=name]").val('');
                    $("#form-checklist").collapse('toggle');
                },
            });
        });
        $(document).on("click", ".delete-checklist", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Checklist successfully deleted.")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });
        var checked = 0;
        var count = 0;
        var percentage = 0;
        $(document).on("change", "#check-list input[type=checkbox]", function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'PUT',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist successfully updated.")}}', 'success');
                },
                error: function (data) {
                    data = data.responseJSON;
                    toastrs('Error', '{{ __("Something is wrong.")}}', 'error');
                }
            });
            taskCheckbox();
        });


        $(document).on('click', '#form-comment button', function (e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name = '{{\Auth::user()->name}}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                        data = JSON.parse(data);

                        var html = "<li class='media mb-20'>" +
                            "                    <div class='media-body'>" +
                            "                        <h5 class='mt-0'>" + name + "</h5>" +
                            "                        " + data.comment +
                            "                           <div class='comment-trash' style=\"float: right\">" +
                            "                               <a href='#' class='btn btn-outline btn-sm red text-muted  delete-comment' data-url='" + data.deleteUrl + "' >" +
                            "                                   <i class='far fa-trash-alt'></i>" +
                            "                               </a>" +

                            "                           </div>" +
                            "                    </div>" +
                            "                </li>";


                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        toastrs('Success', '{{ __("Comment successfully created.")}}', 'success');
                    },
                    error: function (data) {
                        toastrs('Error', '{{ __("Some thing is wrong.")}}', 'error');
                    }
                });
            } else {
                toastrs('Error', '{{ __("Please write comment.")}}', 'error');
            }
        });
        $(document).on("click", ".delete-comment", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Comment Deleted Successfully!")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function (e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('url'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    toastrs('Success', '{{ __("Comment successfully created.")}}', 'success');
                    // console.log(data);
                    var delLink = '';

                    if (data.deleteUrl.length > 0) {
                        delLink = "<a href='#' class='text-danger text-muted delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                            "                                        <i class='dripicons-trash'></i>" +
                            "                                    </a>";
                    }

                    var html = '<li class="media mb-20">\n' +
                        '                                                <div class="media-body">\n' +
                        '                                                    <h5 class="mt-0 mb-1 font-weight-bold"> ' + data.name + '</h5>\n' +
                        '                                                   ' + data.file_size + '' +
                        '                                                    <div class="comment-trash" style="float: right">\n' +
                        '                                                        <a download href="{{asset(Storage::url('tasks'))}}/' + data.file + '" class="btn btn-outline btn-sm blue-madison">\n' +
                        '                                                            <i class="fa fa-download"></i>\n' +
                        '                                                        </a>' +
                        '<a href=\'#\' class="btn btn-outline btn-sm red text-muted delete-comment"  data-url="' + data.deleteUrl + '"><i class="far fa-trash-alt"></i></a>' +

                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                            </li>';
                    $("#comments-file").prepend(html);
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        toastrs('Error', data.message, 'error');
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                }
            });
        });
        $(document).on("click", ".delete-comment-file", function () {

            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("File successfully deleted.")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some thing is wrong.")}}', 'error');
                        }
                    }
                });
            }
        });

    </script>

    <script>

        $(document).on('change', 'select[name=project]', function () {
            var project_id = $(this).val();
            getMilestone(project_id);
            getUser(project_id);
        });

        function getMilestone(project_id) {
            $.ajax({
                url: '{{route('project.getMilestone')}}',
                type: 'POST',
                data: {
                    "project_id": project_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#milestone_id').empty();
                    $('#milestone_id').append('<option value="">{{__('Select Milestone')}}</option>');
                    $.each(data, function (key, value) {
                        $('#milestone_id').append('<option value="' + key + '">' + value + '</option>');
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

                    $('#assign_to').empty();
                    $('#assign_to').append('<option value="">{{__('Select User')}}</option>');
                    $.each(data, function (key, value) {

                        $('#assign_to').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

    </script>
@endpush
@section('page-title')
    {{__('Task')}}
@endsection
@push('css-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Manage Task')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{'Task'}}</li>
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
                                {{ Form::open(array('route' => array('project.all.task'),'method'=>'get')) }}
                                <div class="row">
                                    <div class="col text-right">
                                        <ul class="nav nav-tabs">
                                            <li><a class="active" data-toggle="tab" href="#taskList"> {{__('List')}}</a></li>
                                            <li><a data-toggle="tab" href="#taskKanban"> {{__('Kanban')}}</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('project', __('Project')) }}
                                            {{ Form::select('project', $projectList,!empty($_GET['project'])?$_GET['project']:'', array('class' => 'form-control custom-select')) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::label('status',__('Status'))}}
                                        <select class="form-control custom-select" name="status">
                                            <option value="">{{__('All')}}</option>
                                            @foreach($stageList as $k=>$val)
                                                <option value="{{$k}}" {{isset($_GET['status']) && $_GET['status']==$k?'selected':''}}> {{$val}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{ Form::label('priority', __('Priority')) }}
                                            <select class="form-control custom-select" name="priority">
                                                <option value="">{{__('All')}}</option>
                                                @foreach($priority as $val)
                                                    <option value="{{$val}}" {{isset($_GET['priority']) && $_GET['priority']==$val?'selected':''}}> {{$val}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        {{Form::label('due_date',__('Due Date'))}}
                                        {{Form::date('due_date',isset($_GET['due_date'])?$_GET['due_date']:'',array('class'=>'form-control'))}}
                                    </div>
                                    <div class="col-auto apply-btn">
                                        {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                        <a href="{{route('project.all.task')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                                        <h2 class="h3 mb-0">{{__('Tasks')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('project.task.create',0) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="btn btn-outline-primary btn-sm">
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
                                                        <th>{{__('Title')}}</th>
                                                        <th>{{__('Start date')}}</th>
                                                        <th>{{__('Due date')}}</th>
                                                        <th>{{__('Assigned to')}}</th>
                                                        <th>{{__('Priority')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th class="text-right">{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>

                                                    @foreach($projects as $project)
                                                        @php
                                                            if(empty($_GET['status']) && empty($_GET['priority']) && empty($_GET['due_date'])){
                                                                $tasks=$project->tasks;
                                                            }else{
                                                                $tasks=$project->taskFilter($_GET['status'],$_GET['priority'],$_GET['due_date']);
                                                            }
                                                        @endphp

                                                        @foreach($tasks as $task)
                                                            <tr>
                                                                <td> {{$project->title}}</td>
                                                                <td>{{$task->title}}</td>
                                                                <td> {{\Auth::user()->dateFormat($task->start_date)}}</td>
                                                                <td> {{\Auth::user()->dateFormat($task->due_date)}}</td>
                                                                <td> {{!empty($task->taskUser)?$task->taskUser->name:''}}</td>
                                                                <td>
                                                                    @if($task->priority =='low')
                                                                        <div class="label label-soft-success font-style"> {{ $task->priority }}</div>
                                                                    @elseif($task->priority =='medium')
                                                                        <div class="label label-soft-warning font-style"> {{ $task->priority }}</div>
                                                                    @elseif($task->priority =='high')
                                                                        <div class="label label-soft-danger font-style"> {{ $task->priority }}</div>
                                                                    @endif
                                                                </td>
                                                                <td> {{!empty($task->stages)?$task->stages->name:''}}</td>
                                                                <td class="text-right">
                                                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                                                        <a href="#" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    @endif
                                                                    @if(\Auth::user()->type=='company')
                                                                        <a href="#" data-url="{{ route('project.task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit Task')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                            <i class="far fa-edit"></i>
                                                                        </a>
                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                        {!! Form::close() !!}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="taskKanban" class="tab-pane fade in ">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Tasks')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                            <span class="create-btn">
                                                                <a href="#" data-url="{{ route('project.task.create',0) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="btn btn-outline-primary btn-sm">
                                                                   <i class="fa fa-plus"></i>  {{__('Create')}}
                                                               </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @php
                                                    $json = [];
                                                    foreach ($stages as $stage){
                                                        $json[] = 'task-list-'.$stage->id;
                                                    }
                                                @endphp
                                                <div class="board" data-plugin="dragula" data-containers='{!! json_encode($json) !!}'>
                                                    <div class="row">
                                                        @forelse($stages as $stage)
                                                            @php
                                                                if(empty($_GET['project']) && empty($_GET['priority']) && empty($_GET['due_date'])){
                                                                   $tasks = $stage->allTask;
                                                                }else{
                                                                    $tasks=$stage->allTaskFilter($_GET['project'] , $_GET['priority'],$_GET['due_date']);
                                                                }
                                                            @endphp

                                                            @php  @endphp

                                                            <div class="col-lg-3">
                                                                <div class="lead-head">
                                                                    <div class="">
                                                                        <h4>{{$stage->name}}</h4>
                                                                        <span class="badge">{{count($tasks)}}</span>
                                                                    </div>
                                                                </div>
                                                                <div id="task-list-{{$stage->id}}" data-id="{{$stage->id}}" class="lead-item-body scrollbar-inner">
                                                                    @foreach($tasks as $task)
                                                                        <div class="lead-item card mb-2 mt-0" data-id="{{$task->id}}">
                                                                            <a href="#" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="table-action" data-toggle="tooltip"><h5>{{$task->title}}</h5></a>

                                                                            <div class="table-actions">
                                                                                <div class="dropdown">
                                                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                                    </a>
                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 32px, 0px);">
                                                                                        @if(\Auth::user()->type=='company')
                                                                                            <a class="dropdown-item" href="#" data-url="{{ route('project.task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit Task')}}"> {{__('Edit')}}</a>
                                                                                        @endif

                                                                                        @if(\Auth::user()->type=='company')
                                                                                            <a class="dropdown-item" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();"> {{__('Delete')}}</a>

                                                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                                            {!! Form::close() !!}
                                                                                        @endif

                                                                                        <a class="dropdown-item" href="#" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="table-action" data-toggle="tooltip"> {{__('View')}}</a>

                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                            <p></p>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <p class="card-text small text-muted">
                                                                                        <i class="far fa-calendar"></i> {{\Auth::user()->dateFormat($task->start_date)}}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col text-right">
                                                                                    <p class="card-text small text-muted">
                                                                                        <i class="far fa-calendar"></i> {{\Auth::user()->dateFormat($task->due_date)}}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <p></p>
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <a href="#!" data-toggle="tooltip" class="float-left">
                                                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{!empty($task->taskUser)?$task->taskUser->name:''}}" @if($task->taskUser) src="{{$profile.'/'.$task->taskUser->avatar}}" @else src="{{$profile .'/avatar.png'}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col">
                                                                                    <p> {{$task->taskCompleteCheckListCount()}}/{{$task->taskTotalCheckListCount()}}</p>
                                                                                </div>
                                                                                <div class="col">
                                                                                    <a href="#" class="task-status low">
                                                                                        <small>
                                                                                            @if($task->priority =='low')
                                                                                                <div class="label label-soft-success font-style"> {{ $task->priority }}</div>
                                                                                            @elseif($task->priority =='medium')
                                                                                                <div class="label label-soft-warning font-style"> {{ $task->priority }}</div>
                                                                                            @elseif($task->priority =='high')
                                                                                                <div class="label label-soft-danger font-style"> {{ $task->priority }}</div>
                                                                                            @endif
                                                                                        </small>
                                                                                    </a>
                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                            </div>
                                                        @empty
                                                            <div class="col-md-12 text-center">
                                                                <h4>{{__('No data available')}}</h4>
                                                            </div>
                                                        @endforelse
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
        </div>
    </div>

@endsection


