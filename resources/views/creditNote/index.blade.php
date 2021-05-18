@extends('layouts.admin')
@section('page-title')
    {{__('Credit Notes')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        $(document).on('change', '#invoice', function () {

            var id = $(this).val();
            var url = "{{route('invoice.get')}}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'id': id,

                },
                success: function (data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Credit Notes')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Credit Note')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Credit Note')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('creditNote.create') }}" data-ajax-popup="true" data-title="{{__('Create New Credit Note')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Invoice')}}</th>
                                <th>{{__('Client')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Description')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($invoices as $invoice)
                                @if(!empty($invoice->creditNote))
                                    @foreach ($invoice->creditNote as $creditNote)
                                        <tr>
                                            <td>{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</td>
                                            <td>{{ (!empty($invoice->clients)?$invoice->clients->name:'') }}</td>
                                            <td>{{ Auth::user()->dateFormat($creditNote->date) }}</td>
                                            <td>{{ Auth::user()->priceFormat($creditNote->amount) }}</td>
                                            <td>{{$creditNote->description}}</td>
                                            <td class="table-actions text-right">
                                                <a href="{{ route('invoice.show',Crypt::encrypt($invoice->id)) }}" class="table-action" data-toggle="tooltip" data-original-title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(\Auth::user()->type=='company')
                                                    <a href="#" data-url="{{ route('creditNote.edit',$creditNote->id) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Credit Note')}}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$creditNote->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['creditNote.destroy', $creditNote->id],'id'=>'delete-form-'.$creditNote->id]) !!}
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

