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
    <h6 class="h2 d-inline-block mb-0">{{__('Edit Client')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('client.index')}}">{{__('Client')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        {{ Form::model($client, array('route' => array('client.update', $client->user_id), 'method' => 'PUT')) }}
        <div class="card">
            <div class="card-body">
                <div class="row custmer-detail-wrap">
                    <div class="col-md-6">
                        <h2 class="sub-title col">{{__('Personal Detail')}}</h2>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    {{Form::label('name',__('Name'))}}
                                    {{Form::text('name',$user->name,array('class'=>'form-control font-style'))}}
                                    @error('name')
                                    <span class="invalid-name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{Form::label('mobile',__('Mobile'))}}
                                {{Form::number('mobile',$client->mobile,array('class'=>'form-control'))}}
                                @error('mobile')
                                <span class="invalid-mobile" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                {{Form::label('address_1',__('Address 1'))}}
                                {{Form::textarea('address_1', $client->address_1, ['class'=>'form-control','rows'=>'4'])}}
                                @error('address_1')
                                <span class="invalid-address_1" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                {{Form::label('address_2',__('Address 2'))}}
                                {{Form::textarea('address_2', $client->address_2, ['class'=>'form-control','rows'=>'4'])}}
                                @error('address_2')
                                <span class="invalid-address_2" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                {{Form::label('city',__('City'))}}
                                {{Form::text('city',$client->city,array('class'=>'form-control'))}}
                                @error('city')
                                <span class="invalid-city" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                {{Form::label('state',__('State'))}}
                                {{Form::text('state',$client->state,array('class'=>'form-control'))}}
                                @error('state')
                                <span class="invalid-state" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                {{Form::label('country',__('Country'))}}
                                {{Form::text('country',$client->country,array('class'=>'form-control'))}}
                                @error('country')
                                <span class="invalid-country" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                {{Form::label('zip_code',__('Zip Code'))}}
                                {{Form::text('zip_code',$client->zip_code,array('class'=>'form-control'))}}
                                @error('zip_code')
                                <span class="invalid-zip_code" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="sub-title">{{__('Company Detail')}}</h2>
                        <div class="row">
                            <div class="col-md-12">
                                {{Form::label('company_name',__('Company Name'))}}
                                {{Form::text('company_name',$client->company_name,array('class'=>'form-control'))}}
                                @error('company_name')
                                <span class="invalid-company_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                {{Form::label('website',__('Website'))}}
                                {{Form::text('website',$client->website,array('class'=>'form-control'))}}
                                @error('website')
                                <span class="invalid-website" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                {{Form::label('tax_number',__('Tax Number'))}}
                                {{Form::text('tax_number',$client->tax_number,array('class'=>'form-control'))}}
                                @error('tax_number')
                                <span class="invalid-tax_number" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-10">
                                {{Form::label('notes',__('Notes'))}}
                                {{Form::textarea('notes', $client->notes, ['class'=>'form-control','rows'=>'3'])}}
                                @error('notes')
                                <span class="invalid-notes" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-right">
                    {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

