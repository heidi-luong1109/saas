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
    <h6 class="h2 d-inline-block mb-0">{{\Auth::user()->employeeIdFormat($employee->employee_id)."'s Detail"}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('employee.index')}}">{{__('Employee')}}</a></li>
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
                                <h3 class="mb-0">{{__('Name')}} </h3>  {{$user->name}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('ID')}} </h3> {{\Auth::user()->employeeIdFormat($employee->employee_id)}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Date of Birth')}} </h3>   {{\Auth::user()->dateFormat($employee->dob)}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Mobile')}} </h3> {{$employee->mobile}}
                            </div>
                            <div class="col">
                                <h4 class="mb-0">{{__('Gender')}} </h4>   {{$employee->gender}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h4 class="mb-0">{{__('Address')}} </h4>  {{$employee->address}}
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <h2 class="sub-title">{{__('Company Detail')}}</h2>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Department')}} </h3> {{!empty($employee->departments)?$employee->departments->name:''}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Designation')}} </h3> {{!empty($employee->designations)?$employee->designations->name:''}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Date of Joining')}} </h3> {{\Auth::user()->dateFormat($employee->joining_date)}}
                            </div>
                            <div class="col">
                                <h3 class="mb-0">{{__('Salary Type')}} </h3>{{!empty($employee->salaryType)?$employee->salaryType->name:''}}
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col">
                                <h3 class="mb-0">{{__('Salary')}} </h3> {{\Auth::user()->priceFormat($employee->salary)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

