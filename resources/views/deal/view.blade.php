@extends('layouts.admin')

@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/vendor/dropzone/dist/min/dropzone.min.css')}}">

    <style>
        .custom-checkbox .custom-control-input ~ .custom-control-label {
            font-size: .875rem;
            height: unset;
            cursor: pointer;
        }
    </style>
@endpush
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@push('script-page')

    <script>

        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {

            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('deal.file.upload',$deal->id)}}",
            success: function (file, response) {

                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response, 'error');
                }
            }
        });

        myDropzone2 = new Dropzone("#dropzonewidget2", {

            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('deal.file.upload',$deal->id)}}",
            success: function (file, response) {

                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone2.removeFile(file);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone2.removeFile(file);
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response, 'error');
                }
            }
        });

        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("deal_id", {{$deal->id}});
        });
        myDropzone2.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("deal_id", {{$deal->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "table-action");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "table-action");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='fas fa-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                toastrss('Error', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                toastrss('Error', response.error, 'error');
                            } else {
                                toastrss('Error', response, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);

            html.appendChild(del);

            file.previewTemplate.appendChild(html);

        }

        @foreach($deal->files as $file)

        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('uploads/deal_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('uploads/deal_files')).'/'.$file->file_path}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('deal.file.download',[$deal->id,$file->id])}}", delete: "{{route('deal.file.delete',[$deal->id,$file->id])}}"});

        @endforeach

        @foreach($deal->files as $file)

        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('uploads/deal_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone2.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone2.emit("thumbnail", mockFile, "{{asset(Storage::url('uploads/deal_files')).'/'.$file->file_path}}");
        myDropzone2.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('deal.file.download',[$deal->id,$file->id])}}", delete: "{{route('deal.file.delete',[$deal->id,$file->id])}}"});

        @endforeach


        $('.summernote-simple').keyup(function () {

            $.ajax({
                url: "{{route('deal.note.store',$deal->id)}}",
                data: {_token: $('meta[name="csrf-token"]').attr('content'), notes: $(this).val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        // toastrs('Success', response.success,'success');
                    } else {
                        toastrs('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response, 'error');
                    }
                }
            })
        });


        $(document).on("click", ".task-checkbox", function () {
            var chbox = $(this);
            var lbl = chbox.parent().parent().parent().parent().find('.checklist-title');

            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        chbox.val(response.status);
                        if (response.status) {
                            lbl.addClass('strike');
                            lbl.find('.badge').removeClass('badge-warning').addClass('badge-success');
                        } else {
                            lbl.removeClass('strike');
                            lbl.find('.badge').removeClass('badge-success').addClass('badge-warning');
                        }
                        lbl.find('.badge').html(response.status_label);

                        toastrs('Success', response.success, 'success');
                    } else {
                        toastrs('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response, 'error');
                    }
                }
            })
        });


    </script>
    <script>
        var Fullcalendar = (function () {
            var $calendar = $('[data-toggle="calendar"]');

            function init($this) {
                var events = {!! json_encode($calenderTasks) !!},
                    locale = '{{basename(App::getLocale())}}',

                    options = {
                        header: {
                            right: '',
                            center: '',
                            left: ''
                        },
                        buttonIcons: {
                            prev: 'calendar--prev',
                            next: 'calendar--next'
                        },
                        theme: false,
                        selectable: true,
                        selectHelper: true,
                        editable: true,
                        events: events,

                        dayClick: function (date) {
                            var isoDate = moment(date).toISOString();
                            $('#new-event').modal('show');
                            $('.new-event--title').val('');
                            $('.new-event--start').val(isoDate);
                            $('.new-event--end').val(isoDate);
                        },

                        viewRender: function (view) {
                            var calendarDate = $this.fullCalendar('getDate');
                            var calendarMonth = calendarDate.month();

                            $('.fullcalendar-title').html(view.title);
                        },

                        eventClick: function (event, element) {
                            $('#edit-event input[value=' + event.className + ']').prop('checked', true);
                            $('#edit-event').modal('show');
                            $('.edit-event--id').val(event.id);
                            $('.edit-event--title').val(event.title);
                            $('.edit-event--description').val(event.description);
                        }
                    };

                // Initalize the calendar plugin
                $this.fullCalendar(options);

                //Calendar views switch
                $('body').on('click', '[data-calendar-view]', function (e) {
                    e.preventDefault();

                    $('[data-calendar-view]').removeClass('active');
                    $(this).addClass('active');

                    var calendarView = $(this).attr('data-calendar-view');
                    $this.fullCalendar('changeView', calendarView);
                });


                //Calendar Next
                $('body').on('click', '.fullcalendar-btn-next', function (e) {
                    e.preventDefault();
                    $this.fullCalendar('next');
                });


                //Calendar Prev
                $('body').on('click', '.fullcalendar-btn-prev', function (e) {
                    e.preventDefault();
                    $this.fullCalendar('prev');
                });
            }

            // Init
            if ($calendar.length) {
                init($calendar);
            }

        })();

        $(document).on('click', '.fc-day-grid-event', function (e) {
            e.preventDefault();
            var event = $(this);
            var title = $(this).find('.fc-content .fc-title').html();
            var size = 'md';
            var url = $(this).attr('href');
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);

            $.ajax({
                url: url,
                success: function (data) {
                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');
                },
                error: function (data) {
                    data = data.responseJSON;
                    toastr('Error', data.error, 'error')
                }
            });
        });

        $(document).on("click", ".deal_status", function () {
            var deal_status = $(this).attr('data-status');
            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                type: 'POST',
                data: {deal_status: deal_status, "_token": $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#change-deal-status').submit();
                    location.reload();
                }

            });

        });

    </script>
@endpush
@section('page-title')
    {{__('Deal Detail')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Deal Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('deal.index') }}">{{__('Deal')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$deal->name}}</li>
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
                                    <li><a class="active" data-toggle="tab" href="#general">{{__('General')}}</a></li>
                                    <li><a data-toggle="tab" href="#task">{{__('Task')}}</a></li>
                                    <li><a data-toggle="tab" href="#items">{{__('Items')}}</a></li>
                                    <li><a data-toggle="tab" href="#sources">{{__('Sources')}}</a></li>
                                    <li><a data-toggle="tab" href="#files">{{__('Files')}}</a></li>
                                    <li><a data-toggle="tab" href="#discussion">{{__('Discussion')}}</a></li>
                                    <li><a data-toggle="tab" href="#notes">{{__('Notes')}}</a></li>
                                    <li><a data-toggle="tab" href="#client">{{__('Clients')}}</a></li>
                                    <li><a data-toggle="tab" href="#calls">{{__('Calls')}}</a></li>
                                    <li><a data-toggle="tab" href="#emails">{{__('Emails')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php
                    $tasks = $deal->tasks;
                    $products = $deal->items();
                    $sources = $deal->sources();
                    $calls = $deal->calls;
                    $emails = $deal->emails;
                    ?>

                    <div class="card-body1 notes-page">
                        <div class="tab-content">
                            <div id="general" class="tab-pane fade in active show">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Deal Details')}}</h3>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <div class="table-actions">
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-outline-primary btn-sm" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span class="btn-inner--text">{{$deal->status}}</span>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-160px, 32px, 0px);">

                                                                    @foreach(\App\Deal::$statues as $k=>$status)
                                                                        <a class="dropdown-item deal_status" data-status="{{$k}}" data-url="{{route('deal.change.status',$deal->id)}}" href="#">{{$status}}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            @if(\Auth::user()->type=='company')
                                                                <a href="#" data-url="{{ route('deal.labels',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Label')}}" data-toggle="tooltip" data-original-title="{{__('Label')}}" class="table-action">
                                                                    <i class="fas fa-tags"></i>
                                                                </a>
                                                                <a href="#" data-url="{{ route('deal.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Edit Deal')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('deal-delete-form-{{$deal->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deal.destroy', $deal->id],'id'=>'deal-delete-form-'.$deal->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-header d-flex align-items-center">
                                                <div class="align-items-center">
                                                    <h4>{{$deal->name}}</h4>
                                                    <p>{{$deal->subject}}</p>
                                                    <div class="project-details">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Price')}}</div>
                                                                <div class="font-weight-bold">{{\Auth::user()->priceFormat($deal->price)}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Pipeline')}}</div>
                                                                <div class="font-weight-bold">{{$deal->pipeline->name}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Stage')}}</div>
                                                                <div class="font-weight-bold">{{$deal->stage->name}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Created')}} </div>
                                                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($deal->created_at)}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Status')}} </div>
                                                                <div class="font-weight-bold">
                                                                    @if($deal->status == 'Won')
                                                                        <span class="badge badge-success">{{__($deal->status)}}</span>
                                                                    @elseif($deal->status == 'Loss')
                                                                        <span class="badge badge-danger">{{__($deal->status)}}</span>
                                                                    @else
                                                                        <span class="badge badge-info">{{__($deal->status)}}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @php($labels = $deal->labels())

                                                            <div class="col-auto">
                                                                @if($labels)
                                                                    <div class="tx-gray-500 small">{{__('Label')}} </div>
                                                                    <div class="row">
                                                                        <div class="col-12 mb-2">
                                                                            <div class="text-right">
                                                                                @foreach($labels as $label)
                                                                                    <span class="badge badge-{{$label->color}}">{{$label->name}}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card card-stats">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Task')}}</h5>
                                                                <span class="h2 font-weight-bold mb-0">{{count($tasks)}}</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                                                    <i class="fas fa-tasks"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 mb-0 text-sm">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card card-stats">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Product')}}</h5>
                                                                <span class="h2 font-weight-bold mb-0">{{count($products)}}</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                                                    <i class="fas fa-dolly"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 mb-0 text-sm">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card card-stats">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Source')}}</h5>
                                                                <span class="h2 font-weight-bold mb-0">{{count($sources)}}</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                                                    <i class="fas fa-eye"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 mb-0 text-sm">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="card card-stats">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <h5 class="card-title text-uppercase text-muted mb-0">{{__('Files')}}</h5>
                                                                <span class="h2 font-weight-bold mb-0">{{count($deal->files)}}</span>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                                                    <i class="fas fa-file-alt"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 mb-0 text-sm">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header border-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Users')}}</h3>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-6 text-right">
                                                        <span class="create-btn">
                                                            <a href="#" data-url="{{ route('deal.users.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i>  {{__('Add')}}
                                                            </a>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="table-responsive lead-box">
                                                <table class="table align-items-center table-flush table-striped">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Name')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($deal->users as $user)
                                                        <tr>
                                                            <td class="table-user">
                                                                <img @if($user->avatar) src="{{asset('storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('storage/uploads/avatar/avatar.png')}}" @endif class="avatar rounded-circle mr-3">
                                                                <b>{{$user->name}}</b>
                                                            </td>
                                                            @if($user->type!='company')
                                                                @if(\Auth::user()->type=='company')
                                                                    <td class="table-actions text-right">
                                                                        @if($deal->created_by == \Auth::user()->id)
                                                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('user-delete-form-{{$user->id}}').submit();">
                                                                                <i class="fas fa-trash"></i>
                                                                            </a>
                                                                            {!! Form::open(['method' => 'DELETE',  'route' => ['deal.users.destroy',$deal->id,$user->id],'id'=>'user-delete-form-'.$user->id]) !!}
                                                                            {!! Form::close() !!}
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header border-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Items')}}</h3>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-6 text-right">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('deal.items.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Items')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Light table -->
                                            <div class="table-responsive lead-box">
                                                <table class="table align-items-center table-flush table-striped">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Price')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($deal->items())
                                                        @foreach($deal->items() as $product)
                                                            <tr>
                                                                <td class="table-user">
                                                                    <p class="font-weight-bold" style="font-size:13px;">{{$product->name}}</p>
                                                                </td>
                                                                <td><p class="font-weight-bold" style="font-size:13px;">{{\Auth::user()->priceFormat($product->sale_price)}}</p></td>
                                                                @if(\Auth::user()->type=='company')
                                                                    <td class="table-actions text-right">
                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('product-delete-form-{{$product->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['deal.items.destroy',$deal->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
                                                                        {!! Form::close() !!}
                                                                        </span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0">{{__('Files')}}</h3>
                                            </div>
                                            <div class="card-body lead-box">
                                                <div class="col-md-12 dropzone top-5-scroll" id="dropzonewidget"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="h3 mb-0">{{__('Notes')}}</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="col-md-12">
                                                    <div class="form-group mt-3">
                                                        <textarea class="form-control summernote-simple" id="exampleFormControlTextarea1" rows="10">{!! $deal->notes !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="min-height: 338px">
                                            <div class="card-header">
                                                <h5 class="h3 mb-0">{{__('Activity')}}</h5>
                                            </div>
                                            <div class="card-body p-0 activity">

                                                <ul class="list-group list-group-flush" data-toggle="checklist">
                                                    @foreach($deal->activities as $activity)
                                                        <div class="activity">
                                                            @if($activity->log_type == 'Move')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-warning">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0"> {!! $activity->getRemark() !!}</h5>
                                                                            <small> {{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Add Product')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-info">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Invoice')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-danger">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Add Contact')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-warning">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0"> {!! $activity->getRemark() !!}</h5>
                                                                            <small> {{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Task')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-info">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Upload File')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-danger">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Deal Call')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-info">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Deal Email')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-danger">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @if($calenderTasks)
                                        <div class="col-md-12">
                                            <div class="header header-dark bg-primary  content__title content__title--calendar">
                                                <div class="container-fluid">
                                                    <div class="header-body">
                                                        <div class="row align-items-center py-4">
                                                            <div class="col-lg-6">
                                                                <h6 class="fullcalendar-title h2 text-white d-inline-block mb-0"></h6>

                                                            </div>
                                                            <div class="col-lg-6 mt-3 mt-lg-0 text-lg-right">
                                                                <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                                                                    <i class="fas fa-angle-left"></i>
                                                                </a>
                                                                <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                                                                    <i class="fas fa-angle-right"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                                                                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                                                                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card card-calendar">
                                                <div class="card-header">
                                                    <h5 class="h3 mb-0">{{__('Calendar')}}</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="calendar" data-toggle="calendar" id="calendar"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div id="task" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header border-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Task')}}</h3>
                                                    </div>
                                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                        <div class="col-6 text-right">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('deal.tasks.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Create New Task')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Create')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body p-0">
                                                <!-- List group -->
                                                <ul class="list-group list-group-flush" data-toggle="checklist">
                                                    @foreach($tasks as $task)
                                                        <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                            <div class="checklist-item checklist-item-success">
                                                                <div class="checklist-info">
                                                                    <h3 class="checklist-title mb-0">
                                                                        {{$task->name}}
                                                                        @if($task->status)
                                                                            <div class="badge badge-pill badge-success mb-1">{{__(\App\DealTask::$status[$task->status])}}</div>
                                                                        @else
                                                                            <div class="badge badge-pill badge-warning mb-1">{{__(\App\DealTask::$status[$task->status])}}</div>
                                                                        @endif
                                                                    </h3>
                                                                    <small> {{__(\App\DealTask::$priorities[$task->priority])}} <span class="text-default">●</span> {{Auth::user()->dateFormat($task->date)}} {{Auth::user()->timeFormat($task->time)}}  </small>
                                                                </div>

                                                                @if(\Auth::user()->type=='company')
                                                                    <div class="float-right mt-2">

                                                                        <a href="#" data-url="{{route('deal.tasks.edit',[$deal->id,$task->id])}}" data-ajax-popup="true" data-title="{{__('Edit Task')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                            <i class="far fa-edit"></i>
                                                                        </a>

                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        <a href="#">
                                                                            <label class="custom-toggle custom-toggle-success" for="chk-todo-task-{{$task->id}}">
                                                                                <input class="task-checkbox" type="checkbox" @if($task->status) checked="checked" @endcan type="checkbox" value="{{$task->status}}" id="chk-todo-task-{{$task->id}}" value="{{$task->status}}" data-url="{{route('deal.tasks.update_status',[$deal->id,$task->id])}}">
                                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                            </label>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['deal.tasks.destroy',$deal->id,$task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="items" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header border-0">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Items')}}</h3>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-6 text-right">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('deal.items.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Items')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table align-items-center table-flush table-striped">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Price')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($deal->items())
                                                        @foreach($deal->items() as $product)
                                                            <tr>
                                                                <td class="table-user">
                                                                    <p class="font-weight-bold" style="font-size:13px;">{{$product->name}}</p>
                                                                </td>
                                                                <td><p class="font-weight-bold" style="font-size:13px;">{{\Auth::user()->priceFormat($product->sale_price)}}</p></td>
                                                                @if(\Auth::user()->type=='company')
                                                                    <td class="table-actions text-right">
                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('item-delete-form-{{$product->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['deal.items.destroy',$deal->id,$product->id],'id'=>'item-delete-form-'.$product->id]) !!}
                                                                        {!! Form::close() !!}
                                                                        </span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="sources" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <!-- Card header -->
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Sources')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                       <span class="create-btn">
                                                            <a href="#" data-url="{{ route('deal.sources.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Sources')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i>  {{__('Add')}}
                                                            </a>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table align-items-center table-flush table-striped">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>{{__('Name')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($sources)
                                                        @foreach($sources as $source)
                                                            <tr>
                                                                <td class="table-user">
                                                                    <p class="font-weight-bold" style="font-size:13px;">{{$source->name}}</p>
                                                                </td>
                                                                @if(\Auth::user()->type=='company')
                                                                    <td class="table-actions text-right">
                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('source-delete-form-{{$source->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['deal.sources.destroy',$deal->id,$source->id],'id'=>'source-delete-form-'.$source->id]) !!}
                                                                        {!! Form::close() !!}
                                                                        </span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="files" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="mb-0">{{__('Files')}}</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12 dropzone top-5-scroll" id="dropzonewidget2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="discussion" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Discussion')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                        <div class="col-auto">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('deal.discussions.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Message')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-unstyled list-unstyled-border">
                                                    @foreach($deal->discussions as $discussion)
                                                        <li class="media mb-4">
                                                            <img alt="image" class="mr-3 rounded-circle" width="50" src="@if($discussion->user) {{asset(Storage::url('uploads/avatar')).'/'.$discussion->user->avatar}} @else {{asset(Storage::url('uploads/avatar')).'/avatar.png'}} @endif">
                                                            <div class="media-body">
                                                                <div class="mt-0 mb-1 font-weight-bold">{{!empty($discussion->user)?$discussion->user->name:''}} <small>{{!empty($discussion->user)?$discussion->user->type:''}}</small> <small class="float-right">{{$discussion->created_at->diffForHumans()}}</small></div>
                                                                <div class="text-sm"> {{$discussion->comment}}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
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
                                                <h5 class="h3 mb-0">{{__('Notes')}}</h5>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="col-md-12">
                                                    <div class="form-group mt-3">
                                                        <textarea class="form-control summernote-simple" id="exampleFormControlTextarea1" rows="10">{!! $deal->notes !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="calls" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Calls')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('deal.call.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Call')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
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
                                                        <th>{{__('Subject')}}</th>
                                                        <th>{{__('Call Type')}}</th>
                                                        <th>{{__('Duration')}}</th>
                                                        <th>{{__('User')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($calls as $call)
                                                        <tr>
                                                            <td>{{ $call->subject }}</td>
                                                            <td>{{ ucfirst($call->call_type) }}</td>
                                                            <td>{{ $call->duration }}</td>
                                                            <td>{{ !empty($call->getLeadCallUser)?$call->getLeadCallUser->name:'' }}</td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="text-right">
                                                                    <a href="#" data-url="{{ route('deal.call.edit',[$deal->id,$call->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Calls')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete trigger--fire-modal-1" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('call-delete-form-{{$call->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deal.call.destroy',$deal->id ,$call->id],'id'=>'call-delete-form-'.$call->id]) !!}
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

                            <div id="emails" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Email')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                       <span class="create-btn">
                                                            <a href="#" data-url="{{ route('deal.email.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Email')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i>  {{__('Add')}}
                                                            </a>
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled list-unstyled-border">
                                                    @foreach($emails as $email)
                                                        <li class="media mb-4">
                                                            <img alt="image" class="mr-3 rounded-circle" width="50" src="@if($email->user) {{asset(Storage::url('uploads/avatar')).'/'.$email->user->avatar}} @else {{asset(Storage::url('uploads/avatar')).'/avatar.png'}} @endif">
                                                            <div class="media-body">
                                                                <div class="mt-0 mb-1 font-weight-bold">{{$email->to}} <small>{{!empty($email->user)?$email->user->type:''}}</small> <small class="float-right">{{$email->created_at->diffForHumans()}}</small></div>
                                                                <div class="text-sm">{{$email->subject}}</div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="client" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col">
                                                        <h2 class="h3 mb-0">{{__('Client')}}</h2>
                                                    </div>
                                                    @if(\Auth::user()->type=='company')
                                                        <div class="col-auto">
                                                       <span class="create-btn">
                                                            <a href="#" data-url="{{route('deal.clients.edit',$deal->id)}}" data-ajax-popup="true" data-title="{{__('Add Client')}}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fa fa-plus"></i>  {{__('Add')}}
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
                                                        <th>{{__('Avatar')}}</th>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Email')}}</th>
                                                        @if(\Auth::user()->type=='company')
                                                            <th class="text-right">{{__('Action')}}</th>
                                                        @endif
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach ($deal->clients as $client)
                                                        <tr>
                                                            <td><img alt="image" src="@if(\Auth::user()->avatar) {{$profile.'/'.$client->avatar}} @else {{$profile.'avatar.png'}} @endif" class="rounded-circle mr-1" width="50"></td>
                                                            <td>{{ $client->name }}</td>
                                                            <td>{{ $client->email }}</td>
                                                            @if(\Auth::user()->type=='company')
                                                                <td class="text-right">
                                                                    <a href="#" class="table-action table-action-delete trigger--fire-modal-1" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('client-delete-form-{{$client->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deal.clients.destroy',$deal->id,$client->id],'id'=>'client-delete-form-'.$client->id]) !!}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


