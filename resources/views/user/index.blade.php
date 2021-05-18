@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@section('page-title')
    {{__('User')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('User')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Users')}}</h2>
                            </div>
                            <div class="col-auto">
                                <a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true" data-title="{{__('Create New User')}}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-plus"></i> {{__('Create')}}
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($users as $user)
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="bg-profile"></div>
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#">
                                        <img src="{{!empty($user->avatar)?$profile.'/'.$user->avatar:$profile.'/avatar.png'}}" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                            <div class="d-flex justify-content-between">
                                <a href="#" class="table-action" data-url="{{ route('user.edit',$user->id) }}" data-ajax-popup="true" data-title="{{__('Edit User')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                    <i class="far fa-edit"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id],'id'=>'delete-form-'.$user->id]) !!}
                                {!! Form::close() !!}

                                <a href="#!" class="table-action table-action-delete float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$user->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col">
                                    <div class="card-profile-stats d-flex justify-content-center">
                                        <div>
                                            <span class="heading">{{$user->countEmployees($user->id)}}</span>
                                            <span class="description">{{__('Employees')}}</span>
                                        </div>
                                        <div>
                                            <span class="heading">{{$user->countClients($user->id)}}</span>
                                            <span class="description">{{__('Clients')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="h3">
                                    {{$user->name}}
                                </h5>
                                <div class="h5 font-weight-300">
                                    {{$user->type}}
                                </div>
                                <div class="h5 font-weight-300">
                                    {{$user->email}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="text-left mb-3">
                                        <b class="text-primary font-style">{{!empty($user->currentPlan)?$user->currentPlan->name:''}}</b>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="text-right mb-3">
                                        <a href="#" class="btn btn-primary btn-sm" data-url="{{ route('plan.upgrade',$user->id) }}" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}">{{__('Upgrade Plan')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-left mb-3">
                                        <p class="font-style">{{__('Plan Expired : ') }} {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

