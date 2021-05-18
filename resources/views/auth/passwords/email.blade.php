@extends('layouts.auth')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <div class="form-group mt-10 mr-2">
                    <select name="language" id="language" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        @foreach(Utility::languages() as $language)
                            <option @if($lang == $language) selected @endif value="{{ route('change.langPass',$language) }}">{{Str::upper($language)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="header py-7 py-lg-8 pt-lg-9">
        <div class="container">
            <div class="header-body text-center mb-5">
                <div class="row justify-content-center">
                    <a class="navbar-brand" href="#">
                        <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" class="auth-logo">
                    </a>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{__('Reset Password')}}</small>
                        </div>
                        {{Form::open(array('route'=>'password.email','method'=>'post','id'=>'loginForm'))}}
                        <div class="form-group mb-3">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                </div>
                                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            {{Form::submit(__('Reset Password'),array('class'=>'btn btn-primary my-4','id'=>'saveBtn'))}}
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <p class="text-muted">{{__('Back to')}} <a href="{{ route('login') }}" class="text-muted ml-1"><b>{{ __('Log In') }}</b></a></p>
                    </div>
                </div>
                <div class="row mt-3">

                    <div class="col-12 text-center">
                        {{ (Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :config('app.name', 'CRMGo') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
