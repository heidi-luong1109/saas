@extends('layouts.admin')
@section('page-title')
    {{__('Leave')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        $(document).on('change', '#employee_id', function () {

            var employee_id = $(this).val();

            $.ajax({
                url: '{{route('leave.jsoncount')}}',
                type: 'POST',
                data: {
                    "employee_id": employee_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {

                    $('#leave_type').empty();
                    $('#leave_type').append('<option value="">{{__('Select Leave Type')}}</option>');

                    $.each(data, function (key, value) {

                        if (value.total_leave >= value.days) {
                            $('#leave_type').append('<option value="' + value.id + '" disabled>' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                        } else {
                            $('#leave_type').append('<option value="' + value.id + '">' + value.title + '&nbsp(' + value.total_leave + '/' + value.days + ')</option>');
                        }
                    });

                }
            });
        });

    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Leave')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Leave')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'leave','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-md-3">
                                    {{ Form::label('employee', __('Employee')) }}
                                    {{ Form::select('employee', $employees,isset($_GET['employee'])?$_GET['employee']:'', array('class' => 'form-control custom-select')) }}
                                </div>
                            @endif
                            <div class="col-md-3">
                                {{Form::label('start_date',__('Start Date'))}}
                                {{Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-3">
                                {{Form::label('end_date',__('End Date'))}}
                                {{Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('leave.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <h2 class="h3 mb-0">{{__('Manage Leave')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('leave.create') }}" data-ajax-popup="true" data-title="{{__('Create New Leave')}}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                @if(\Auth::user()->type!='employee')
                                    <th>{{__('Employee')}}</th>
                                @endif
                                <th>{{__('Leave Type')}}</th>
                                <th>{{__('Applied On')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>
                                <th>{{__('Total Days')}}</th>
                                <th>{{__('Leave Reason')}}</th>
                                <th>{{__('status')}}</th>
                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                    <th class="text-right" width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    @if(\Auth::user()->type!='employee')
                                        <td>{{ !empty($leave->user)?$leave->user->name:'' }}</td>
                                    @endif
                                    <td>{{ !empty($leave->leaveType)?$leave->leaveType->title:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->applied_on )}}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->start_date ) }}</td>
                                    <td>{{ \Auth::user()->dateFormat($leave->end_date )  }}</td>
                                    @php
                                        $startDate = new \DateTime($leave->start_date);
                                        $endDate   = new \DateTime($leave->end_date);
                                        $total_leave_days = !empty($startDate->diff($endDate))?$startDate->diff($endDate)->days:0;
                                    @endphp
                                    <td>{{ $total_leave_days }}</td>
                                    <td>{{ $leave->leave_reason }}</td>
                                    <td>
                                        @if($leave->status=="Pending")
                                            <div class="badge badge-warning">{{ $leave->status }}</div>
                                        @elseif($leave->status=="Approve")
                                            <div class="badge badge-success">{{ $leave->status }}</div>
                                        @else($leave->status=="Reject")
                                            <div class="badge badge-danger">{{ $leave->status }}</div>
                                        @endif
                                    </td>
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <td class="text-right">
                                            @if(\Auth::user()->type=='company')
                                                <a href="#" class="table-action" data-url="{{ route('leave.action',$leave->id) }}" data-ajax-popup="true" data-title="{{__('Leave Detail')}}" data-toggle="tooltip" data-original-title="{{__('Approve/Reject')}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                                <a href="#" class="table-action" data-url="{{ route('leave.edit',$leave->id) }}" data-ajax-popup="true" data-title="{{__('Edit Leave')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="far fa-edit"></i>
                                                </a>

                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$leave->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leave.destroy', $leave->id],'id'=>'delete-form-'.$leave->id]) !!}
                                                {!! Form::close() !!}
                                            @endif

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

