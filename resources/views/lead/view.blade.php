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
@push('script-page')
    <script>


        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {

            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('lead.file.upload',$lead->id)}}",
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
            url: "{{route('lead.file.upload',$lead->id)}}",
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
            formData.append("lead_id", {{$lead->id}});
        });
        myDropzone2.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("lead_id", {{$lead->id}});
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
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);

            html.appendChild(del);

            file.previewTemplate.appendChild(html);

        }

        @foreach($lead->files as $file)

        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('uploads/lead_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('uploads/lead_files')).'/'.$file->file_path}}");
        myDropzone.emit("complete", mockFile);

        @if(\Auth::user()->type=='company')
        dropzoneBtn(mockFile, {download: "{{route('lead.file.download',[$lead->id,$file->id])}}", delete: "{{route('lead.file.delete',[$lead->id,$file->id])}}"});
        @endif
        @endforeach

        @foreach($lead->files as $file)

        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('uploads/lead_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone2.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone2.emit("thumbnail", mockFile, "{{asset(Storage::url('uploads/lead_files')).'/'.$file->file_path}}");
        myDropzone2.emit("complete", mockFile);
        @if(\Auth::user()->type=='company')
        dropzoneBtn(mockFile, {download: "{{route('lead.file.download',[$lead->id,$file->id])}}", delete: "{{route('lead.file.delete',[$lead->id,$file->id])}}"});
        @endif
        @endforeach


        $('.summernote-simple').keyup(function () {

            $.ajax({
                url: "{{route('lead.note.store',$lead->id)}}",
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

    </script>
@endpush
@section('page-title')
    {{__('Lead Detail')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Lead Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('lead.index') }}">{{__('Lead')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$lead->name}}</li>
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
                                    <li><a data-toggle="tab" href="#items">{{__('Items')}}</a></li>
                                    <li><a data-toggle="tab" href="#sources">{{__('Sources')}}</a></li>
                                    <li><a data-toggle="tab" href="#files">{{__('Files')}}</a></li>
                                    <li><a data-toggle="tab" href="#discussion">{{__('Discussion')}}</a></li>
                                    <li><a data-toggle="tab" href="#notes">{{__('Notes')}}</a></li>
                                    <li><a data-toggle="tab" href="#calls">{{__('Calls')}}</a></li>
                                    <li><a data-toggle="tab" href="#emails">{{__('Emails')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @php
                        $products = $lead->items();
                        $sources = $lead->sources();
                        $calls = $lead->calls;
                      $emails = $lead->emails;
                    @endphp

                    <div class="card-body1 notes-page">
                        <div class="tab-content">
                            <div id="general" class="tab-pane fade in active show">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h3 class="mb-0">{{__('Lead Details')}}</h3>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <div class="table-actions">
                                                            @if(\Auth::user()->type=='company')
                                                                @if(!empty($deal))
                                                                    <a href="@if($deal->is_active) {{route('deal.show',\Crypt::encrypt($deal->id))}} @else # @endif" class="table-action">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="#" data-url="{{ route('lead.convert.deal',$lead->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Convert ['.$lead->subject.'] To Deal')}}" data-toggle="tooltip" data-original-title="{{__('Convert To Deal')}}" class="table-action"><i class="fas fa-exchange-alt"></i></a>
                                                                @endif

                                                                <a href="#" data-url="{{ route('lead.label',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Label')}}" data-toggle="tooltip" data-original-title="{{__('Label')}}" class="table-action">
                                                                    <i class="fas fa-tags"></i>
                                                                </a>

                                                                <a href="#" data-url="{{ route('lead.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('lead-delete-form-{{$lead->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['lead.destroy', $lead->id],'id'=>'lead-delete-form-'.$lead->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="progress m-0" style="height: 4px;">
                                                <div class="progress-bar bg-primary" style="width: {{$precentage}}%;"></div>
                                            </div>
                                            <div class="card-header d-flex align-items-center">
                                                <div class="align-items-center">
                                                    <h4>{{$lead->name}}</h4>
                                                    <p>{{$lead->subject}}</p>
                                                    <div class="project-details">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Pipeline')}}</div>
                                                                <div class="font-weight-bold">{{!empty($lead->pipeline)?$lead->pipeline->name:''}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Stage')}}</div>
                                                                <div class="font-weight-bold">{{!empty($lead->stage)?$lead->stage->name:''}}</div>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="tx-gray-500 small">{{__('Created')}} </div>
                                                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($lead->created_at)}}</div>
                                                            </div>
                                                            @php($labels = $lead->labels())

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
                                                                <span class="h2 font-weight-bold mb-0">{{count($lead->files)}}</span>
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
                                                                <a href="#" data-url="{{ route('lead.users.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="btn btn-outline-primary btn-sm">
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
                                                        <th class="text-right">{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($lead->users as $user)
                                                        <tr>
                                                            <td class="table-user">
                                                                <img @if($user->avatar) src="{{asset('storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('storage/uploads/avatar/avatar.png')}}" @endif class="avatar rounded-circle mr-3">
                                                                <b>{{$user->name}}</b>
                                                            </td>
                                                            <td class="table-actions text-right">
                                                                @if($user->type!='company')
                                                                    @if($lead->created_by == \Auth::user()->id )
                                                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('user-delete-form-{{$user->id}}').submit();">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['lead.users.destroy',$lead->id,$user->id],'id'=>'user-delete-form-'.$user->id]) !!}
                                                                        {!! Form::close() !!}
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            </td>
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
                                                            <a href="#" data-url="{{ route('lead.items.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Items')}}" class="btn btn-outline-primary btn-sm">
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
                                                        <th class="text-right">{{__('Action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($lead->items())
                                                        @foreach($lead->items() as $product)
                                                            <tr>
                                                                <td class="table-user">
                                                                    <p class="font-weight-bold" style="font-size:13px;">{{$product->name}}</p>
                                                                </td>
                                                                <td><p class="font-weight-bold" style="font-size:13px;">{{\Auth::user()->priceFormat($product->sale_price)}}</p></td>
                                                                <td class="table-actions text-right">
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('item-delete-form-{{$product->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE',  'route' => ['lead.items.destroy',$lead->id,$product->id],'id'=>'item-delete-form-'.$product->id]) !!}
                                                                    {!! Form::close() !!}
                                                                    </span>
                                                                </td>
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
                                                        <textarea class="form-control summernote-simple" id="exampleFormControlTextarea1" rows="10">{!! $lead->notes !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="h3 mb-0">{{__('Activity')}}</h5>
                                            </div>
                                            <div class="card-body p-0 activity">

                                                <ul class="list-group list-group-flush" data-toggle="checklist">
                                                    @foreach($lead->activities as $activity)
                                                        <div class="activity">
                                                            @if($activity->log_type == 'Move')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-warning">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0"> {!! $activity->getLeadRemark() !!}</h5>
                                                                            <small> {{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Add Product')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-info">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getLeadRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Invoice')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-danger">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getLeadRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Add Contact')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-warning">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0"> {!! $activity->getLeadRemark() !!}</h5>
                                                                            <small> {{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Create Task')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-info">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getLeadRemark() !!}</h5>
                                                                            <small>{{$activity->created_at->diffForHumans()}} </small>
                                                                        </div>

                                                                    </div>
                                                                </li>
                                                            @elseif($activity->log_type == 'Upload File')
                                                                <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                                                    <div class="checklist-item checklist-item-danger">
                                                                        <div class="checklist-info">
                                                                            <h5 class="checklist-title mb-0">{!! $activity->getLeadRemark() !!}</h5>
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
                                                                <a href="#" data-url="{{ route('lead.items.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Items')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Light table -->
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
                                                    @if($lead->items())
                                                        @foreach($lead->items() as $product)
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
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['lead.items.destroy',$lead->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
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
                                                            <a href="#" data-url="{{ route('lead.sources.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Sources')}}" class="btn btn-outline-primary btn-sm">
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
                                                                        {!! Form::open(['method' => 'DELETE',  'route' => ['lead.sources.destroy',$lead->id,$source->id],'id'=>'source-delete-form-'.$source->id]) !!}
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
                                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                                        <div class="col-auto">
                                                           <span class="create-btn">
                                                                <a href="#" data-url="{{ route('lead.discussions.create',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Message')}}" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fa fa-plus"></i>  {{__('Add')}}
                                                                </a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-unstyled list-unstyled-border">
                                                    @foreach($lead->discussions as $discussion)
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
                                                        <textarea class="form-control summernote-simple" id="exampleFormControlTextarea1" rows="10">{!! $lead->notes !!}</textarea>
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
                                                                <a href="#" data-url="{{ route('lead.call.create',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Call')}}" class="btn btn-outline-primary btn-sm">
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
                                                                    <a href="#" data-url="{{ route('lead.call.edit',[$lead->id,$call->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Calls')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('call-delete-form-{{$call->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['lead.call.destroy',$lead->id ,$call->id],'id'=>'call-delete-form-'.$call->id]) !!}
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
                                                                <a href="#" data-url="{{ route('lead.email.create',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Email')}}" class="btn btn-outline-primary btn-sm">
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
                                                                <div class="text-small">{{$email->subject}}</div>
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
                </div>
            </div>
        </div>
    </div>

@endsection


