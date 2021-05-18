@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@section('page-title')
    {{__('Employee')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Employee')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Employee')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'employee','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('department', __('Department')) }}
                                {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('designation', __('Designation')) }}
                                {{ Form::select('designation', $designation,isset($_GET['designation'])?$_GET['designation']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('employee.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Employees')}}</h2>
                            </div>
                            <div class="col-auto">
                                <a href="#" data-url="{{ route('employee.create') }}" data-ajax-popup="true" data-title="{{__('Create New Employee')}}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-plus"></i> {{__('Create')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Employee ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Salary')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)

                                <tr>
                                    <td><a href="{{route('employee.show',\Crypt::encrypt($employee->id))}}" class="btn btn-sm btn-primary"> {{!empty($employee->employeeDetail)? \Auth::user()->employeeIdFormat($employee->employeeDetail->employee_id):''}}</a></td>
                                    <td class="table-user">
                                        <img src="{{$profile.'/'.$employee->avatar}}" class="avatar rounded-circle mr-3 tbl-avatar">
                                        <b>{{$employee->name}}</b>
                                    </td>
                                    <td>{{$employee->email}}</td>
                                    <td>{{!empty($employee->employeeDetail)? !empty($employee->employeeDetail->departments)?$employee->employeeDetail->departments->name:'':''}}</td>
                                    <td>{{!empty($employee->employeeDetail)? !empty($employee->employeeDetail->designations)?$employee->employeeDetail->designations->name:'':''}}</td>
                                    <td>{{!empty($employee->employeeDetail)? \Auth::user()->priceFormat($employee->employeeDetail->salary):''}}</td>
                                    <td class="table-actions text-right">
                                        <a href="{{route('employee.show',\Crypt::encrypt($employee->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{route('employee.edit',\Crypt::encrypt($employee->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>

                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$employee->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id],'id'=>'delete-form-'.$employee->id]) !!}
                                        {!! Form::close() !!}


                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

