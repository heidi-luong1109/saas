@extends('layouts.admin')
@section('page-title')
    {{__('Attendance Report')}}
@endsection
@push('css-page')
    <style>
        .card .table td, .card .table th {
            padding-right: 0.8rem !important;
            padding-left: 0.9rem !important;
        }
    </style>
@endpush
@push('script-page')

    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();

        }

    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Attendance Report')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Attendance Report')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.attendance'),'method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('month',__('Month'))}}
                                {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('department', __('Department')) }}
                                {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('report.attendance')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                                <a href="#" onclick="saveAsPDF();" class="btn btn-icon icon-left btn-outline-info pdf-btn btn-sm" id="download-buttons">
                                    {{__('Download')}}
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div id="printableArea">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-wrap">
                            <div class="card-body">
                                <input type="hidden" value="{{  $data['curMonth'].' '.__('Attendance Report of').' '. $data['department'].' '.'Department'}}" id="filename">

                                <div class="row">
                                    <div class="col">
                                        {{__('Report')}} : <h5>{{__('Attendance Summary')}}</h5>
                                    </div>
                                    @if($data['department']!='All')
                                        <div class="col">
                                            {{__('Department')}} : <h5>{{$data['department'] }}</h5>
                                        </div>
                                    @endif
                                    <div class="col">
                                        {{__('Duration')}} : <h5>{{$data['curMonth']}}</h5>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Attendance')}}</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <h4> {{__('Total Present')}} : <strong>{{$data['totalPresent']}} </strong></h4>
                                </div>
                                <div class="col text-right">
                                    <h4>  {{__('Total leave')}} : <strong>{{$data['totalLeave']}} </strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Overtime')}}</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <h4> {{__('Total overtime in hours')}} : <strong>{{number_format($data['totalOvertime'],2)}} </strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Early Leave')}}</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <h4> {{__('Total early leave in hours')}} : <strong>{{number_format($data['totalEarlyLeave'],2)}} </strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Employee Late')}}</h4>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <h4> {{__('Total late in hours')}} : <strong>{{number_format($data['totalLate'],2)}} </strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive py-4">
                                <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        <th class="active">{{__('Name')}}</th>
                                        @foreach($dates as $date)
                                            <th>{{$date}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employeesAttendance as $attendance)
                                        <tr>
                                            <td>{{$attendance['name']}}</td>
                                            @foreach($attendance['status'] as $status)
                                                <td>
                                                    @if($status=='P')
                                                        <i class="badge badge-success">{{__('P')}}</i>
                                                    @elseif($status=='L')
                                                        <i class="badge badge-danger">{{__('L')}}</i>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

