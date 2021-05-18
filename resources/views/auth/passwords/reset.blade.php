@extends('layouts.auth')

@section('content')
    <div class="card card-primary">
        <!-- title-->
        <div class="card-header"><h4>{{ __('Reset Password') }}</h4></div>
        <div class="card-body">
            {{Form::open(array('route'=>'password.update','method'=>'post','id'=>'loginForm'))}}
            <div class="form-group">
                {{Form::label('email',__('Email Address'))}}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                @error('email')
                <span class="invalid-email text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {{Form::label('password',__('Password'))}}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Your Password')))}}
                @error('password')
                <span class="invalid-password text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {{Form::label('password_confirmation',__('Confirm Password'))}}
                {{Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>__('Enter Your Password')))}}
                @error('password_confirmation')
                <span class="invalid-password_confirmation text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-0 text-center">
                {{Form::submit(__('Reset Password'),array('class'=>'btn btn-primary btn-block','id'=>'resetBtn'))}}
            </div>
            {{Form::close()}}

        </div>
    </div>
@endsection
