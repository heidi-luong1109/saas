@extends('layouts.admin')
@section('page-title')
    {{__('Coupon Detail')}}
@endsection

@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Coupon Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('coupon.index')}}">{{__('Coupon')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Coupon Detail')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Coupon Detail')}}</h2>
                            </div>
                            <div class="col-auto">

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th> {{__('User')}}</th>
                                <th> {{__('Date')}}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th> {{__('User')}}</th>
                                <th> {{__('Date')}}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach ($userCoupons as $userCoupon)
                                <tr class="font-style">
                                    <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
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

