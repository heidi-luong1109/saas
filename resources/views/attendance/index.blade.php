@extends('layouts.admin')
@section('page-title')
    {{__('Attendance')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Attendance')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Attendance')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'attendance','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-3">
                                {{Form::label('date',__('Date'))}}
                                {{Form::date('date',isset($_GET['date'])?$_GET['date']:'',array('class'=>'form-control'))}}
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-md-3">
                                    {{ Form::label('employee', __('Employee')) }}
                                    {{ Form::select('employee', $employees,isset($_GET['employee'])?$_GET['employee']:'', array('class' => 'form-control custom-select')) }}
                                </div>
                            @endif
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('attendance.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Attendance')}}</h2>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                            <tr>
                                @if(\Auth::user()->type!='employee')
                                    <th>{{__('Employee')}}</th>
                                @endif
                                <th>{{__('Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Clock In')}}</th>
                                <th>{{__('Clock Out')}}</th>
                                <th>{{__('Late')}}</th>
                                <th>{{__('Early Leaving')}}</th>
                                <th>{{__('Overtime')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    @if(\Auth::user()->type!='employee')
                                        <td>{{!empty($attendance->user)?$attendance->user->name:''}}</td>
                                    @endif
                                    <td>{{ \Auth::user()->dateFormat($attendance->date) }}</td>
                                    <td>{{ $attendance->status }}</td>
                                        <td>{{($attendance->clock_in!='00:00:00')?\Auth::user()->timeFormat( $attendance->clock_in):'00:00:00' }}</td>
                                        <td>{{ ($attendance->clock_out !='00:00:00') ?\Auth::user()->timeFormat( $attendance->clock_out):'00:00:00' }}</td>
                                    <td>{{ $attendance->late }}</td>
                                    <td>{{ $attendance->early_leaving }}</td>
                                    <td>{{ $attendance->overtime }}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="text-right">
                                            <a href="#" class="table-action" data-url="{{ route('attendance.edit',$attendance->id) }}" data-ajax-popup="true" data-title="{{__('Edit Attendance')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$attendance->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['attendance.destroy', $attendance->id],'id'=>'delete-form-'.$attendance->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endif
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

