@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Order')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Order')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Orders')}}</h2>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Coupon')}}</th>
                                <th class="text-right">{{__('Invoice')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->created_at->format('d M Y')}}</td>
                                    <td>{{$order->user_name}}</td>
                                    <td>{{$order->plan_name}}</td>
                                    <td>{{env('CURRENCY_SYMBOL').$order->price}}</td>
                                    <td>{{$order->payment_type}}</td>
                                    <td>
                                        @if($order->payment_status == 'succeeded')
                                            <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                                        @else
                                            <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                                        @endif
                                    </td>

                                    <td>{{!empty($order->total_coupon_used)? !empty($order->total_coupon_used->coupon_detail)?$order->total_coupon_used->coupon_detail->code:'':''}}</td>

                                    <td class="text-center">
                                        @if($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                            <a href="{{$order->receipt}}" title="Invoice" target="_blank" class=""><i class="fas fa-file-invoice"></i> </a>
                                        @elseif($order->receipt =='free coupon')
                                            <p>{{__('Used 100 % discount coupon code.')}}</p>
                                        @elseif($order->payment_type == 'Manually')
                                            <p>{{__('Manually plan upgraded by super admin')}}</p>
                                        @else
                                            --
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

