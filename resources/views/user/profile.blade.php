@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Profile')}}
@endsection
@push('css-page')
    <link href="{{ asset('assets/module/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('script-page')
    <script src="{{ asset('assets/module/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Profile')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Profile')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-sidebar">
                            <div class="portlet light profile-sidebar-portlet ">
                                <div class="profile-userpic">
                                    <img alt="image" src="{{(!empty($userDetail->avatar))? $profile.'/'.$userDetail->avatar : $profile.'/avatar.png'}}" class="img-responsive user-profile" class="img-responsive user-profile">
                                </div>
                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name font-style"> {{$userDetail->name}}</div>
                                    <div class="profile-usertitle-job font-style"> {{$userDetail->type}}</div>
                                    <div class="profile-usertitle-job"> {{$userDetail->email}}</div>
                                </div>
                                <div class="profile-usermenu">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs">
                                    <li><a class="active" data-toggle="tab" href="#home">{{__('Personal Info')}}</a></li>
                                    @if(\Auth::user()->type=='client')
                                        <li><a data-toggle="tab" href="#company">{{__('Company Info')}}</a></li>
                                    @endif
                                    <li><a data-toggle="tab" href="#menu1">{{__('Change Password')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body notes-page">
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active show">
                                {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
                                <div class="row">
                                    @if(\Auth::user()->type=='client')
                                        @php $client=$userDetail->clientDetail; @endphp
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                {{Form::label('name',__('Name'))}}
                                                {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                                                @error('name')
                                                <span class="invalid-name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('email',__('Email'))}}
                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                            @error('email')
                                            <span class="invalid-email" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            {{Form::label('mobile',__('Mobile'))}}
                                            {{Form::number('mobile',$client->mobile,array('class'=>'form-control'))}}
                                            @error('mobile')
                                            <span class="invalid-mobile" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('address_1',__('Address 1'))}}
                                            {{Form::textarea('address_1', $client->address_1, ['class'=>'form-control','rows'=>'4'])}}
                                            @error('address_1')
                                            <span class="invalid-address_1" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('address_2',__('Address 2'))}}
                                            {{Form::textarea('address_2', $client->address_2, ['class'=>'form-control','rows'=>'4'])}}
                                            @error('address_2')
                                            <span class="invalid-address_2" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            {{Form::label('city',__('City'))}}
                                            {{Form::text('city',$client->city,array('class'=>'form-control'))}}
                                            @error('city')
                                            <span class="invalid-city" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('state',__('State'))}}
                                            {{Form::text('state',$client->state,array('class'=>'form-control'))}}
                                            @error('state')
                                            <span class="invalid-state" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('country',__('Country'))}}
                                            {{Form::text('country',$client->country,array('class'=>'form-control'))}}
                                            @error('country')
                                            <span class="invalid-country" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('zip_code',__('Zip Code'))}}
                                            {{Form::text('zip_code',$client->zip_code,array('class'=>'form-control'))}}
                                            @error('zip_code')
                                            <span class="invalid-zip_code" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::label('name',__('Name'))}}
                                                {{Form::text('name',null,array('class'=>'form-control font-style'))}}
                                                @error('name')
                                                <span class="invalid-name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {{Form::label('email',__('Email'))}}
                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                            @error('email')
                                            <span class="invalid-email" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-lg-12 mt-10">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""></div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 10px;"></div>
                                            <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> {{__('Select image')}} </span>
                                                <span class="fileinput-exists"> {{__('Change')}} </span>
                                                <input type="hidden"><input type="file" name="profile" id="logo">
                                            </span>
                                                <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-right">
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                </div>
                                {{Form::close()}}
                            </div>

                            @if(\Auth::user()->type=='client')
                                <div id="company" class="tab-pane fade in">
                                    {{Form::model($userDetail,array('route' => array('client.update.company'), 'method' => 'put'))}}
                                    <div class="row">
                                        @php $client=$userDetail->clientDetail; @endphp

                                        <div class="col-md-4">
                                            {{Form::label('company_name',__('Company Name'))}}
                                            {{Form::text('company_name',$client->company_name,array('class'=>'form-control'))}}
                                            @error('company_name')
                                            <span class="invalid-company_name" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            {{Form::label('website',__('Website'))}}
                                            {{Form::text('website',$client->website,array('class'=>'form-control'))}}
                                            @error('website')
                                            <span class="invalid-website" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
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
                                        <div class="col-lg-12 text-right">
                                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
                                            {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                        </div>
                                    </div>
                                    {{Form::close()}}
                                </div>
                            @endif

                            <div id="menu1" class="tab-pane fade">
                                {{Form::model($userDetail,array('route' => array('update.password',$userDetail->id), 'method' => 'put'))}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{Form::label('current_password',__('Current Password'))}}
                                            {{Form::password('current_password',null,array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
                                            @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::label('new_password',__('New Password'))}}
                                        {{Form::password('new_password',null,array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
                                        @error('new_password')
                                        <span class="invalid-new_password" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        {{Form::label('confirm_password',__('Re-type New Password'))}}
                                        {{Form::password('confirm_password',null,array('class'=>'form-control','placeholder'=>__('Enter Re-type New Password')))}}
                                        @error('confirm_password')
                                        <span class="invalid-confirm_password" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12 text-right">
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

