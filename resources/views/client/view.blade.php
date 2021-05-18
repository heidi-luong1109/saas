@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@section('page-title')
    {{__('Client')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{\Auth::user()->clientIdFormat($client->client_id)."'s Detail"}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('client.index')}}">{{__('Client')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('View')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row custmer-detail-wrap">
                    <div class="col-md-6">
                        <h2 class="sub-title col">{{__('Personal Detail')}}</h2>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Name')}} </h3> {{$user->name}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('ID')}} </h3> {{\Auth::user()->clientIdFormat($client->client_id)}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Mobile')}} </h3> {{$client->mobile}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Address 1')}} </h3> {{$client->address_1}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Address 2')}} </h3> {{$client->address_2}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('City')}} </h3> {{$client->city}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('State')}} </h3> {{$client->state}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Country')}} </h3> {{$client->country}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Zip Code')}} </h3> {{$client->zip_code}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="sub-title">{{__('Company Detail')}}</h2>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Company Name')}} </h3>  {{$client->company_name}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Web Site')}} </h3>  <a href="{{$client->website}}" target="_blank">{{$client->website}}</a>
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Tax Number')}} </h3>  {{$client->tax_number}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h4 class="mb-0">{{__('Note')}} </h4>  {{$client->notes}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

