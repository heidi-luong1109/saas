@extends('layouts.admin')
@section('page-title')
    {{__('Email Templates')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script type="text/javascript">

        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastr('Error', response.error, 'error');
                    } else {
                        toastr('Error', response, 'error');
                    }
                }
            })
        });

    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Email Templates')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Email Templates')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="col">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h2 class="h3 mb-0">{{__('Manage Email Templates')}}</h2>
                                </div>
{{--                                @if(\Auth::user()->type=='super admin')--}}
{{--                                    <div class="col-auto">--}}
{{--                                        <span class="create-btn">--}}
{{--                                           <a href="#" data-url="{{route('email_template.create')}}" data-ajax-popup="true" data-title="{{__('Create New Email Template')}}" class="btn btn-outline-primary btn-sm">--}}
{{--                                                <i class="fa fa-plus"></i>  {{__('Create')}}--}}
{{--                                            </a>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                        <div class="table-responsive py-4">
                            <table class="table table-flush" id="datatable-basic">
                                <thead class="thead-light">
                                <tr>
                                    <th class="w-85"> {{__('Name')}}</th>
                                    <th class="text-right"> {{__('Action')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($EmailTemplates as $EmailTemplate)
                                    <tr>
                                        <td>{{ $EmailTemplate->name }}</td>
                                        <td>
                                            @if(\Auth::user()->type=='super admin')
                                                <a href="{{ route('manage.email.language',[$EmailTemplate->id,\Auth::user()->lang]) }}" class="table-action float-right">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            @if(\Auth::user()->type=='company')
                                                <label class="custom-toggle float-right email-custom-toggle">
                                                    <input type="checkbox" class="email-template-checkbox" id="email_tempalte_{{$EmailTemplate->template->id}}" @if($EmailTemplate->template->is_active == 1) checked="checked" @endcan type="checkbox" value="{{$EmailTemplate->template->is_active}}" data-url="{{route('status.email.language',[$EmailTemplate->template->id])}}">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
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
    </div>
@endsection


