@extends('layouts.admin')
@section('page-title')
    {{__('Plan')}}
@endsection
@php
    $dir= asset(Storage::url('uploads/plan'));
@endphp
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Plan')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Plan')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Plans')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='super admin')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('plan.create') }}" data-ajax-popup="true" data-title="{{__('Create New Plan')}}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row plan-div">
                            @foreach($plans as $plan)
                                <div class="col-sm-4 col-md-4">
                                    <div class="plan-item">
                                        <h4> {{$plan->name}}</h4>
                                        <div class="img-wrap">
                                            <img class="plan-img" src="{{$dir.'/'.$plan->image}}">
                                        </div>
                                        <h3>
                                            {{env('CURRENCY_SYMBOL').$plan->price}}
                                        </h3>
                                        <p class="font-style">{{$plan->duration}}</p>
                                        <div class="text-center mb-10">
                                            @if(\Auth::user()->type=='company' && \Auth::user()->plan == $plan->id)

                                                <div class="text-left">
                                                    <p>{{__('Expired : ')}} {{\Auth::user()->plan_expire_date ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date):__('Unlimited')}}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-left">
                                            <p>{{$plan->description}}</p>
                                        </div>
                                        <ul>
                                            <li>
                                                <i class="fas fa-user-tie"></i>
                                                <p>{{$plan->max_employee}} {{__('Employee')}}</p>
                                            </li>
                                            <li>
                                                <i class="fas fa-user-plus"></i>
                                                <p>{{$plan->max_client}} {{__('Client')}}</p>
                                            </li>
                                            <li>
                                                @if( \Auth::user()->type == 'super admin')
                                                    <a title="Edit Plan" href="#" class="btn btn-outline-primary btn-sm" data-url="{{ route('plan.edit',$plan->id) }}" data-ajax-popup="true" data-title="{{__('Edit Plan')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="far fa-edit"></i></a>
                                                @endif

                                                @if(($plan->id != \Auth::user()->plan) && \Auth::user()->type!='super admin' )
                                                    @if($plan->price > 0)
                                                        <a title="Buy Plan" class="btn btn-outline-primary btn-sm" href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}">
                                                            <i class="fa fa-cart-plus"></i>
                                                        </a>
                                                    @else
                                                        <a class="view-btn">{{__('Free')}}</a>
                                                    @endif
                                                @endif

                                                @if(\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                                    <div class="text-center">
                                                        <a class="view-success-btn">
                                                            <a class="view-btn"> {{__('Active')}}</a>
                                                        </a>
                                                    </div>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

