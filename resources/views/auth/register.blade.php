@extends('layouts.auth')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <div class="form-group mt-10 mr-2">
                    <select name="language" id="language" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        @foreach(Utility::languages() as $language)
                            <option @if($lang == $language) selected @endif value="{{ route('register',$language) }}">{{Str::upper($language)}}</option>
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
        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary border-0">

                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Free Sign Up') }}</small>
                        </div>
                        {{Form::open(array('route'=>'register','method'=>'post','id'=>'loginForm'))}}
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                </div>
                                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Your Name')))}}
                                @error('name')
                                <span class="error invalid-name text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative mb-3">
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
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Your Password')))}}
                                @error('password')
                                <span class="error invalid-password text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-merge input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                </div>
                                {{Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>__('Enter Your Confirm Password')))}}
                                @error('password')
                                <span class="error invalid-password_confirmation text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            {{Form::submit(__('Sign Up'),array('class'=>'btn btn-primary mt-4','id'=>'saveBtn'))}}
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        @if (Route::has('password.request'))
                            <a class="text-light" href="{{ route('login') }}">
                                {{ __('Already Have Account? Log In') }}
                            </a>
                        @endif
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
