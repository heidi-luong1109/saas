@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@section('page-title')
    {{__('Employee')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Edit Employee')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('employee.index')}}">{{__('Employee')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        {{ Form::model($employee, array('route' => array('employee.update', $employee->user_id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data')) }}

        <div class="card">
            <div class="card-body">
                <div class="row custmer-detail-wrap">
                    <div class="col-md-6">
                        <h2 class="sub-title col">{{__('Personal Detail')}}</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('name', __('Name')) }}
                                    {{ Form::text('name',$user->name, array('class' => 'form-control','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('mobile', __('Mobile')) }}
                                    {{ Form::text('mobile',$employee->mobile, array('class' => 'form-control','required'=>'required')) }}
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('dob', __('Date of Birth')) !!}
                                    {!! Form::date('dob', null, ['class' => 'form-control','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    {!! Form::label('gender', __('Gender')) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">

                                                <input type="radio" id="customRadio5" name="gender" value="Male" class="custom-control-input" {{($employee->gender == 'Male')?'checked':'checked'}}>
                                                <label class="custom-control-label" for="customRadio5">{{__('Male')}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio mb-3">
                                                <input type="radio" id="customRadio6" name="gender" value="Female" class="custom-control-input" {{($employee->gender == 'Female')?'checked':''}}>
                                                <label class="custom-control-label" for="customRadio6">{{__('Female')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('address', __('Address')) }}
                                    {{ Form::textarea('address',$employee->address, array('class' => 'form-control','required'=>'required','rows'=>'3')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="sub-title">{{__('Company Detail')}}</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('emp_id', __('Employee ID')) !!}
                                    {!! Form::text('emp_id', \Auth::user()->employeeIdFormat($employee->employee_id), ['class' => 'form-control','readonly']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('department', __('Department')) }}
                                    {{ Form::select('department', $department,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('designation', __('Designation')) }}
                                    {{ Form::select('designation', $designation,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('joining_date', __('Date of Joining')) !!}
                                    {!! Form::date('joining_date', null, ['class' => 'form-control','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('exit_date', __('Date of Exit')) !!}
                                    {!! Form::date('exit_date', null, ['class' => 'form-control','required'=>'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('salary_type', __('Salary Type')) }}
                                    {{ Form::select('salary_type', $salaryType,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('salary', __('Salary')) !!}
                                    {!! Form::number('salary', null, ['class' => 'form-control']) !!}
                                </div>
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

