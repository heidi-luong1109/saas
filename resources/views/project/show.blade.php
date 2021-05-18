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

        $(document).on("click", ".status", function () {
            var status = $(this).attr('data-id');
            var url = $(this).attr('data-url');

            $.ajax({
                url: url,
                type: 'POST',
                data: {status: status, "_token": $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#change-project-status').submit();
                    location.reload();
                }
            });
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
@endpush
@section('page-title')
    {{__('Project Detail')}}
@endsection
@push('css-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Project Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">{{__('Project')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$project->title}}</li>
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

                            <div class="col-12 text-right">
                                <ul class="nav nav-tabs">
                                    <li><a class="active" data-toggle="tab" href="#overview">{{__('Overview')}}</a></li>
                                    <li><a data-toggle="tab" href="#taskList"> {{__('Task List')}}</a></li>
                                    <li><a data-toggle="tab" href="#taskKanban"> {{__('Task Kanban')}}</a></li>
                                    <li><a data-toggle="tab" href="#milestone">{{__('Milestone')}}</a></li>
                                    <li><a data-toggle="tab" href="#notes">{{__('Notes')}}</a></li>
                                    <li><a data-toggle="tab" href="#file">{{__('Files')}}</a></li>
                                    <li><a data-toggle="tab" href="#comment">{{__('Comments')}}</a></li>
                                    <li><a data-toggle="tab" href="#clientFeedback"> {{__('Client Feedback')}}</a></li>
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                        <li><a data-toggle="tab" href="#invoice">{{__('Invoice')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <li><a data-toggle="tab" href="#timesheet">{{__('Timesheets')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                        <li><a data-toggle="tab" href="#payment">{{__('Payment')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#expense">{{__('Expense')}}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body1 notes-page">
                        <div class="tab-content">
                            <div id="overview" class="tab-pane fade in active show">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h3 class="mb-0">{{__('Projects Details')}}</h3>
                                                                    </div>
                                                                    @if(\Auth::user()->type=='company')
                                                                        <div class="col-6 text-right">
                                                                            <div class="table-actions">
                                                                                <div class="dropdown">
                                                                                    <a href="#" class="btn btn-outline-primary btn-sm" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                        <span class="btn-inner--text">{{\App\Project::$projectStatus[$project->status]}}</span>
                                                                                    </a>

                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-160px, 32px, 0px);">
                                                                                        @foreach($projectStatus as $k=>$status)
                                                                                            <a class="dropdown-item status" data-id="{{$k}}" data-url="{{route('project.status',$project->id)}}" href="#">{{$status}}</a>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>

                                                                                <a href="{{ route('project.edit',\Crypt::encrypt($project->id)) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                                    <i class="far fa-edit"></i>
                                                                                </a>
                                                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('project-delete-form-{{$project->id}}').submit();">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </a>
                                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['project.destroy', $project->id],'id'=>'project-delete-form-'.$project->id]) !!}
                                                                                {!! Form::close() !!}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="progress m-0" style="height: 3px;">
                                                                <div class="progress-bar bg-primary" style="width: 53%;"></div>
                                                            </div>
                                                            <div class="card-header d-flex align-items-center">
                                                                <div class="align-items-center">
                                                                    <h4>{{$project->title}}</h4>
                                                                    <p>{{$project->description}}</p>
                                                                    <div class="project-details">
                                                                        <div class="row">
                                                                            <div class="col-auto">
                                                                                <div class="tx-gray-500 small">{{__('Start Date')}}</div>
                                                                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($project->start_date)}}</div>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <div class="tx-gray-500 small">{{__('Due Date')}}</div>
                                                                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($project->due_date)}}</div>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <div class="tx-gray-500 small">{{__('Budget')}}</div>
                                                                                <div class="font-weight-bold">{{\Auth::user()->priceFormat($project->price)}}</div>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <div class="tx-gray-500 small">{{__('Expense')}}</div>
                                                                                <div class="font-weight-bold">{{\Auth::user()->priceFormat($totalExpense)}}</div>
                                                                            </div>
                                                                            <div class="col-auto">
                                                                                <div class="tx-gray-500 small">{{__('Client')}}</div>
                                                                                <div class="font-weight-bold">{{!empty($project->clients)?$project->clients->name:''}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="row">

                                                                    <div class="col-md-12">
                                                                        <div class="project-cment-section">
                                                                            <div class="cment">
                                                                                <span class="badge">{{count($comments)}}</span>
                                                                                <div class="tx-gray-500 small">{{__('Comments')}}</div>
                                                                            </div>
                                                                            <div class="cment">
                                                                                <span class="badge">{{count($project->projectUser())}}</span>
                                                                                <div class="tx-gray-500 small">{{__('Members')}}</div>
                                                                            </div>
                                                                            <div class="cment">
                                                                                <span class="badge">{{$daysleft}}</span>
                                                                                <div class="tx-gray-500 small">{{__('Days Left')}}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="card">
                                                            <div class="card-header border-0">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <h3 class="mb-0">{{__('Project members')}}</h3>
                                                                    </div>
                                                                    @if(\Auth::user()->type=='company')
                                                                        <div class="col-6 text-right">
                                                                            <a href="#" data-url="{{ route('project.user',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="btn btn-outline-primary btn-sm">
                                                                                <i class="fas fa-plus"></i> {{__('Add User')}}
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <!-- Light table -->
                                                            <div class="table-responsive project-member-box">
                                                                <table class="table align-items-center table-flush table-striped">
                                                                    <thead class="thead-light">
                                                                    <tr>
                                                                        <th>{{__('Name')}}</th>
                                                                        <th>{{__('Task')}}</th>
                                                                        @if(\Auth::user()->type=='company')
                                                                            <th class="text-right">{{__('Action')}}</th>
                                                                        @endif
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($project->projectUser() as $user)
                                                                        @php $totalTask= $project->user_project_total_task($user->project_id,$user->user_id) @endphp
                                                                        @php $completeTask= $project->user_project_comlete_task($user->project_id,$user->user_id,($project->project_last_stage())?$project->project_last_stage()->id:'' ) @endphp
                                                                        <tr>
                                                                            <td class="table-user">
                                                                                <a href="#" class="font-weight-bold">{{$user->name}}</a>
                                                                                <p class="font-weight-bold" style="font-size:13px;">{{$user->email}}</p>
                                                                            </td>
                                                                            <td>{{$completeTask.'/'.$totalTask}}</td>
                                                                            @if(\Auth::user()->type=='company')
                                                                                <td class="table-actions text-right">
                                                                                    <a href="{{ route('employee.show',\Crypt::encrypt($user->user_id)) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('project-user-delete-form-{{$user->id}}').submit();">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </a>
                                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.user.destroy', $project->id,$user->user_id],'id'=>'project-user-delete-form-'.$user->id]) !!}
                                                                                    {!! Form::close() !!}
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="h3 mb-0">{{__('Activity')}}</h5>
                                                    </div>
                                                    <div class="card-body p-0 project-activity">
                                                        <ul class="list-group list-group-flush" data-toggle="checklist">
                                                            @foreach($project->activities as $activity)
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-warning">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0"> {{ $activity->log_type }}</h5>
                                                                            <p class="m-0">{!! $activity->getRemark() !!}</p>
                                                                            <small> <i class="far fa-calendar-alt"></i><span class="ml-1"> {{date('d M Y H:i', strtotime($activity->created_at))}}  </span> </small>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="taskKanban" class="tab-pane fade in">
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
                                                                <a href="#" data-url="{{ route('project.task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="btn btn-outline-primary btn-sm">
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
                                                        @foreach($stages as $stage)
                                                            @php $tasks =$stage->tasks($project->id) @endphp
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
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="taskList" class="tab-pane fade in">
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
                                                                <a href="#" data-url="{{ route('project.task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="btn btn-outline-primary btn-sm">
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
                                                    @foreach($project->tasks as $task)
                                                        <tr>
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
                                                                <a href="#" data-url="{{ route('project.task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Detail')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                @if(\Auth::user()->type=='company')
                                                                    <a href="#" data-url="{{ route('project.task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit Task')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task2-delete-form-{{$task->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.task.destroy', $task->id],'id'=>'task2-delete-form-'.$task->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="milestone" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Milestone')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                            <a href="#" data-url="{{ route('project.milestone.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Milestone')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i> {{__('Create')}}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Title')}}</th>
                                                        <th>{{__('Cost')}}</th>
                                                        <th>{{__('Due Date')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th width="20%">{{__('Description')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($milestones as $milestone)
                                                        <tr>
                                                            <td>{{$milestone->title}}</td>
                                                            <td> {{\Auth::user()->priceFormat($milestone->cost)}}</td>
                                                            <td> {{\Auth::user()->dateFormat($milestone->due_date)}}</td>
                                                            <td>
                                                                @if($milestone->status =='complete')
                                                                    <div class="label label-soft-success font-style"> {{ $milestone->status }}</div>
                                                                @elseif($milestone->status =='incomplete')
                                                                    <div class="label label-soft-danger font-style"> {{ $milestone->status }}</div>
                                                                @endif
                                                            </td>
                                                            <td> {{$milestone->description}}</td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="text-right">
                                                                    <a href="#" data-url="{{ route('project.milestone.edit',$milestone->id) }}" data-ajax-popup="true" data-title="{{__('Edit Milestone')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('milestone-delete-form-{{$milestone->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'milestone-delete-form-'.$milestone->id]) !!}
                                                                    {!! Form::close() !!}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="notes" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Notes')}}</h2>
                                                    </div>
                                                    <div class="col-auto">
                                                        <a href="#" data-url="{{ route('project.note.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Notes')}}" class="btn btn-outline-primary btn-sm">
                                                            <i class="fa fa-plus"></i> {{__('Create')}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th width="10%">{{__('Created Date')}}</th>
                                                        <th width="30%">{{__('Title')}}</th>
                                                        <th>{{__('Description')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right" width="10%">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($notes as $note)
                                                        <tr>
                                                            <td> {{\Auth::user()->dateFormat($note->created_at)}}</td>
                                                            <td>{{$note->title}}</td>
                                                            <td> {{$note->description}}</td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="text-right">
                                                                    <a href="#" data-url="{{ route('project.note.edit',[$project->id,$note->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Note')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('note-delete-form-{{$note->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.note.destroy', $project->id,$note->id],'id'=>'note-delete-form-'.$note->id]) !!}
                                                                    {!! Form::close() !!}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="file" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('File')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company' || \Auth::user()->type == 'client')
                                                        <div class="col-auto">
                                                            <a href="#" data-url="{{ route('project.file.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New File')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i> {{__('Create')}}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Created Date')}}</th>
                                                        <th>{{__('File')}}</th>
                                                        <th width="30%">{{__('Description')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($files as $file)
                                                        <tr>
                                                            <td> {{\Auth::user()->dateFormat($file->created_at)}}</td>
                                                            <td><a target="_blank" href="{{asset(Storage::url('uploads/files')).'/'.$file->file}}">{{$file->file}}</a></td>
                                                            <td> {{$file->description}}</td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="text-right">
                                                                    <a href="#" data-url="{{ route('project.file.edit',[$project->id,$file->id]) }}" data-ajax-popup="true" data-title="{{__('Edit File')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('file-delete-form-{{$file->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.file.destroy', $project->id,$file->id],'id'=>'file-delete-form-'.$file->id]) !!}
                                                                    {!! Form::close() !!}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="comment" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Comment')}}</h2>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="comment-body">
                                                        @foreach($comments as $comment)
                                                            <div class="media media-comment">
                                                                <img alt="Image placeholder" class="avatar avatar-lg media-comment-avatar rounded-circle" src="{{!empty($comment->commentUser)?$profile.'/'.$comment->commentUser->avatar:$profile.'avatar.png'}}">
                                                                <div class="media-body">
                                                                    <div class="media-comment-text">
                                                                        <h6 class="h5 mt-0">{{!empty($comment->commentUser)?$comment->commentUser->name:''}}</h6>
                                                                        <p class="text-sm lh-160">{{$comment->comment}}</p>
                                                                        <div class="icon-actions">
                                                                            <a href="#">
                                                                                <span class="text-muted">{{$comment->created_at}}</span>
                                                                            </a>

                                                                            @if(!empty($comment->file))
                                                                                <a href="#" class="like active">
                                                                                    <i class="ni ni-cloud-download-95"></i>
                                                                                    <a href="{{asset(Storage::url('uploads/files')).'/'.$comment->file}}" download="" class="text-muted">{{__('Download')}}</a>
                                                                                </a>
                                                                            @endif
                                                                            <a href="#">
                                                                                <i class="ni ni-curved-next"></i>
                                                                                <a href="#" data-url="{{route('project.comment.reply',[$project->id,$comment->id])}}" data-ajax-popup="true" data-title="{{__('Create Comment Reply')}}" class="text-muted">{{__('Reply')}}</a>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    @foreach($comment->subComment as $subComment)
                                                                        <div class="media media-comment inner-comment">
                                                                            <img alt="Image placeholder" class="avatar avatar-lg media-comment-avatar rounded-circle" src="{{!empty($comment->commentUser)?$profile.'/'.$comment->commentUser->avatar:$profile.'avatar.png'}}">
                                                                            <div class="media-body">
                                                                                <div class="media-comment-text">
                                                                                    <h6 class="h5 mt-0">{{!empty($subComment->commentUser)?$subComment->commentUser->name:''}}</h6>
                                                                                    <p class="text-sm lh-160">{{$subComment->comment}}</p>
                                                                                    <div class="icon-actions">
                                                                                        <a href="#">
                                                                                            <span class="text-muted">{{$subComment->created_at}}</span>
                                                                                        </a>
                                                                                        @if(!empty($subComment->file))
                                                                                            <a href="#" class="like active">
                                                                                                <i class="ni ni-cloud-download-95"></i>
                                                                                                <a href="{{asset(Storage::url('uploads/files')).'/'.$subComment->file}}" download="" class="text-muted">{{__('Download')}}</a>
                                                                                            </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <hr>
                                                    <div class="media align-items-center">
                                                        <img alt="Image placeholder" class="avatar avatar-lg rounded-circle mr-4" src="{{!empty(\Auth::user()->avatar)?$profile.'/'.\Auth::user()->avatar:$profile.'avatar.png'}}">
                                                        <div class="media-body">
                                                            {{ Form::open(array('route' => array('project.comment.store',$project->id),'enctype'=>"multipart/form-data")) }}
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <textarea class="form-control" name="comment" placeholder="Write your comment" rows="1"></textarea>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                                                </div>
                                                                <div class="col-md-2 text-right">
                                                                    {{Form::submit(__('Post'),array('class'=>'btn btn-primary'))}}
                                                                </div>
                                                            </div>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="clientFeedback" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Client Feedback')}}</h2>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="comment-body">
                                                        @foreach($feedbacks as $feedback)
                                                            <div class="media media-comment">
                                                                <img alt="Image placeholder" class="avatar avatar-lg media-comment-avatar rounded-circle" src="{{!empty($comment->commentUser)?$profile.'/'.$comment->commentUser->avatar:$profile.'avatar.png'}}">
                                                                <div class="media-body">
                                                                    <div class="media-comment-text">
                                                                        <h6 class="h5 mt-0">{{!empty($feedback->feedbackUser)?$feedback->feedbackUser->name:''}}</h6>
                                                                        <p class="text-sm lh-160">{{$feedback->feedback}}</p>
                                                                        <div class="icon-actions">
                                                                            <a href="#">
                                                                                <span class="text-muted">{{$feedback->created_at}}</span>
                                                                            </a>

                                                                            @if(!empty($feedback->file))
                                                                                <a href="#" class="like active">
                                                                                    <i class="ni ni-cloud-download-95"></i>
                                                                                    <a href="{{asset(Storage::url('uploads/files')).'/'.$feedback->file}}" download="" class="text-muted">{{__('Download')}}</a>
                                                                                </a>
                                                                            @endif
                                                                            <a href="#">
                                                                                <i class="ni ni-curved-next"></i>
                                                                                <a href="#" data-url="{{route('project.client.feedback.reply',[$project->id,$feedback->id])}}" data-ajax-popup="true" data-title="{{__('Create Feedback Reply')}}" class="text-muted">{{__('Reply')}}</a>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    @foreach($feedback->subFeedback as $subFeedback)
                                                                        <div class="media media-comment inner-comment">
                                                                            <img alt="Image placeholder" class="avatar avatar-lg media-comment-avatar rounded-circle" src="{{!empty($feedback->feedbackUser)?$profile.'/'.$feedback->feedbackUser->avatar:$profile.'avatar.png'}}">
                                                                            <div class="media-body">
                                                                                <div class="media-comment-text">
                                                                                    <h6 class="h5 mt-0">{{!empty($subFeedback->feedbackUser)?$subFeedback->feedbackUser->name:''}}</h6>
                                                                                    <p class="text-sm lh-160">{{$subFeedback->feedback}}</p>
                                                                                    <div class="icon-actions">
                                                                                        <a href="#">
                                                                                            <span class="text-muted">{{$subFeedback->created_at}}</span>
                                                                                        </a>
                                                                                        @if(!empty($subFeedback->file))
                                                                                            <a href="#" class="like active">
                                                                                                <i class="ni ni-cloud-download-95"></i>
                                                                                                <a href="{{asset(Storage::url('uploads/files')).'/'.$subFeedback->file}}" download="" class="text-muted">{{__('Download')}}</a>
                                                                                            </a>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <hr>
                                                    <div class="media align-items-center">
                                                        <img alt="Image placeholder" class="avatar avatar-lg rounded-circle mr-4" src="{{!empty(\Auth::user()->avatar)?$profile.'/'.\Auth::user()->avatar:$profile.'avatar.png'}}">
                                                        <div class="media-body">
                                                            {{ Form::open(array('route' => array('project.client.feedback.store',$project->id),'enctype'=>"multipart/form-data")) }}
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <textarea class="form-control" name="feedback" placeholder="Write your feedback" rows="1"></textarea>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    {{ Form::file('file', null, array('class' => 'form-control','required'=>'required')) }}
                                                                </div>
                                                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                                    <div class="col-md-2 text-right">
                                                                        {{Form::submit(__('Post'),array('class'=>'btn btn-primary'))}}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="invoice" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">

                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Invoices')}}</h2>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Invoice ID')}}</th>
                                                        <th>{{__('Issue Date')}}</th>
                                                        <th>{{__('Due Date')}}</th>
                                                        <th>{{__('Total')}}</th>
                                                        <th>{{__('Due')}}</th>
                                                        <th>{{__('Status')}}</th>
                                                        <th class="text-right">{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($invoices as $invoice)
                                                        <tr>
                                                            <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                                            <td>{{\Auth::user()->dateFormat($invoice->issue_date)}}</td>
                                                            <td>{{\Auth::user()->dateFormat($invoice->due_date)}}</td>
                                                            <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                                            <td>{{\Auth::user()->priceFormat($invoice->getDue())}}</td>
                                                            <td>
                                                                @if($invoice->status == 0)
                                                                    <span class="badge badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                                                @elseif($invoice->status == 1)
                                                                    <span class="badge badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                                                @elseif($invoice->status == 2)
                                                                    <span class="badge badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                                                @elseif($invoice->status == 3)
                                                                    <span class="badge badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                                                @elseif($invoice->status == 4)
                                                                    <span class="badge badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="table-actions text-right">
                                                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                                    <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @endif

                                                                @if(\Auth::user()->type=='company')
                                                                    <a href="#!" data-url="{{ route('invoice.edit',$invoice->id) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="timesheet" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Timesheets')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                            <a href="#" data-url="{{ route('project.timesheet.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Timesheet')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i> {{__('Log Time')}}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Member')}}</th>
                                                        <th>{{__('Task')}}</th>
                                                        <th>{{__('Start Date')}}</th>
                                                        <th>{{__('Start Time')}}</th>
                                                        <th>{{__('End Date')}}</th>
                                                        <th>{{__('End Time')}}</th>
                                                        <th>{{__('Notes')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($timesheets as $timesheet)
                                                        <tr>
                                                            <td>{{!empty($timesheet->users)?$timesheet->users->name:''}}</td>
                                                            <td> {{!empty($timesheet->tasks)?$timesheet->tasks->title:''}}</td>
                                                            <td>{{\Auth::user()->dateFormat($timesheet->start_date)}}</td>
                                                            <td>{{\Auth::user()->timeFormat($timesheet->start_time)}}</td>
                                                            <td>{{\Auth::user()->dateFormat($timesheet->end_date)}}</td>
                                                            <td>{{\Auth::user()->timeFormat($timesheet->end_time)}}</td>
                                                            <td><a href="#" data-url="{{ route('project.timesheet.note',[$project->id,$timesheet->id]) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Timesheet Notes')}}"><i class="fa fa-comment"></i></a></td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="table-actions text-right">
                                                                    <a href="#" data-url="{{ route('project.timesheet.edit',[$project->id,$timesheet->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Timesheet')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('timesheet-delete-form-{{$timesheet->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['project.timesheet.destroy', $project->id,$timesheet->id],'id'=>'timesheet-delete-form-'.$timesheet->id]) !!}
                                                                    {!! Form::close() !!}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="payment" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Payment')}}</h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Transaction ID')}}</th>
                                                        <th>{{__('Invoice ID')}}</th>
                                                        <th>{{__('Payment Date')}}</th>
                                                        <th>{{__('Payment Method')}}</th>
                                                        <th>{{__('Payment Type')}}</th>
                                                        <th>{{__('Notes')}}</th>
                                                        <th>{{__('Amount')}}</th>
                                                        <th>{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($invoices as $invoice)
                                                        @foreach($invoice->payments as $payment)
                                                            <tr>
                                                                <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}} </td>
                                                                <td>{{$payment->transaction}} </td>
                                                                <td>{{\Auth::user()->dateFormat($payment->date)}} </td>
                                                                <td>{{!empty($payment->payments)?$payment->payments->name:''}} </td>
                                                                <td>{{$payment->payment_type}} </td>
                                                                <td>{{$payment->notes}} </td>
                                                                <td> {{\Auth::user()->priceFormat(($payment->amount))}}</td>
                                                                <td width="7%" class="text-right">
                                                                    <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
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
                            <div id="expense" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Expenses')}}</h2>
                                                    </div>
                                                    <div class="col-auto">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive py-4">
                                                <table class="table table-flush" id="datatable-basic">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th> {{__('Date')}}</th>
                                                        <th> {{__('Amount')}}</th>
                                                        <th> {{__('User')}}</th>
                                                        <th> {{__('Attachment')}}</th>
                                                        <th> {{__('Description')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach ($project->expenses as $expense)
                                                        <tr class="font-style">
                                                            <td>{{  Auth::user()->dateFormat($expense->date)}}</td>
                                                            <td>{{  Auth::user()->priceFormat($expense->amount)}}</td>
                                                            <td>{{  (!empty($expense->users)?$expense->users->name:'')}}</td>
                                                            <td>
                                                                @if(!empty($expense->attachment))
                                                                    <a href="{{asset(Storage::url('uploads/attachment/'. $expense->attachment))}}" target="_blank">{{  $expense->attachment}}</a>
                                                                @else
                                                                    --
                                                                @endif
                                                            </td>
                                                            <td>{{  $expense->description}}</td>

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


