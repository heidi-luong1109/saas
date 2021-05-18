@extends('layouts.admin')
@section('page-title')
    {{__('Invoice')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        $(document).on('change', 'select[name=client]', function () {
            var client_id = $(this).val();
            getClientProject(client_id);
        });

        function getClientProject(client_id) {
            $.ajax({
                url: '{{route('invoice.client.project')}}',
                type: 'POST',
                data: {
                    "client_id": client_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#project').empty();
                    $.each(data, function (key, value) {
                        $('#project').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        $(document).on('click', '.type', function () {
            var type = $(this).val();
            if (type == 'Project') {
                $('.project-field').removeClass('d-none')
                $('.project-field').addClass('d-block');
            } else {
                $('.project-field').addClass('d-none')
                $('.project-field').removeClass('d-block');
            }
        });


    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Invoice')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Invoice')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'invoice','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('status',__('Status'))}}
                                <select class="form-control custom-select" name="status">
                                    <option value="">{{__('All')}}</option>

                                    @foreach($status as $k=>$val)
                                        <option value="{{$k}}" {{isset($_GET['status']) && $_GET['status'] == $k?'selected':''}}> {{$val}} </option>
                                    @endforeach
                                </select>
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
                                <a href="{{route('invoice.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Invoice')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                <span class="create-btn">
                                   <a href="#" data-url="{{ route('invoice.create') }}" data-ajax-popup="true" data-title="{{__('Create New Invoice')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>#</th>
                                @if(\Auth::user()->type!='client')
                                    <th>{{__('Client')}}</th>
                                @endif
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Total')}}</th>
                                <th>{{__('Due')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Type')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td><a class="btn btn-outline-primary btn-sm" href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}">{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</a></td>
                                    @if(\Auth::user()->type!='client')
                                        <td>{{!empty($invoice->clients)?$invoice->clients->name:''}}</td>
                                    @endif
                                    <td>{{\Auth::user()->dateFormat($invoice->issue_date)}}</td>
                                    <td>{{\Auth::user()->dateFormat($invoice->due_date)}}</td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getDue())}}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge badge-default">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 5)
                                            <span class="badge badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    <td>{{$invoice->type}}</td>
                                    <td class="table-actions text-right">
                                        <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(\Auth::user()->type=='company')
                                            <a href="#!" data-url="{{ route('invoice.edit',$invoice->id) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Invoice')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('invoice-delete-form-{{$invoice->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'invoice-delete-form-'.$invoice->id]) !!}
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
@endsection

