@extends('layouts.admin')
@section('page-title')
    {{__('Leave Report')}}
@endsection
@push('css-page')
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
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save();

        }

        $(document).ready(function () {
            var filename = $('#filename').val();
            $('#report-dataTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: filename
                    },
                    {
                        extend: 'pdf',
                        title: filename
                    }, {
                        extend: 'print',
                        title: filename
                    }, {
                        extend: 'csv',
                        title: filename
                    }
                ],
                language: dataTabelLang,
            });
        });
    </script>

    <script>
        $('input[name="type"]:radio').on('change', function (e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.year').addClass('d-none');
                $('.year').removeClass('d-block');
            } else {
                $('.year').addClass('d-block');
                $('.year').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');

    </script>

@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Leave Report')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Leave Report')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.leave'),'method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <label for="gender">{{__('Type')}}</label>
                                </div>
                                <div class="row">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary month-label {{isset($_GET['type']) && $_GET['type']=='monthly' ?'active':''}}">
                                            <input type="radio" name="type" value="monthly" class="form-control monthly" {{isset($_GET['type']) && $_GET['type']=='monthly' ?'checked':''}}> {{__('Monthly')}}
                                        </label>
                                        <label class="btn btn-secondary year-label {{isset($_GET['type']) && $_GET['type']=='yearly' ?'active':''}}">
                                            <input type="radio" name="type" value="yearly" class="yearly" {{isset($_GET['type']) && $_GET['type']=='yearly' ?'checked':''}}> {{__('Yearly')}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 month">
                                {{Form::label('month',__('Month'))}}
                                {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2 year d-none">
                                {{ Form::label('year', __('Year')) }}
                                <select class="form-control custom-select" id="year" name="year" tabindex="-1" aria-hidden="true">
                                    @for($filterYear['starting_year']; $filterYear['starting_year'] <= $filterYear['ending_year']; $filterYear['starting_year']++)
                                        <option {{(isset($_GET['year']) && $_GET['year'] == $filterYear['starting_year'] ?'selected':'')}} {{(!isset($_GET['year']) && date('Y') == $filterYear['starting_year'] ?'selected':'')}} value="{{$filterYear['starting_year']}}">{{$filterYear['starting_year']}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('department', __('Department')) }}
                                {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('report.leave')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <input type="hidden" value="{{  $filterYear['dateYearRange'].' '.$filterYear['type'].' '.__('Leave Report of').' '. $filterYear['department'].' '.'Department'}}" id="filename">
                                <div class="row">
                                    <div class="col">
                                        {{__('Report')}} : <h4>{{__('Finance Summary')}}</h4>
                                    </div>
                                    @if($filterYear['department']!='All')
                                        <div class="col">
                                            {{__('Department')}} : <h4>{{$filterYear['department'] }}</h4>
                                        </div>
                                    @endif
                                    <div class="col">
                                        {{__('Duration')}} : <h4>{{$filterYear['dateYearRange']}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Approves Leaves')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{$filter['totalApproved']}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Rejected Leaves')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{$filter['totalReject']}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Pending Leaves')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 ">{{$filter['totalPending']}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">

                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="report-dataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Employee ID')}}</th>
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Approved Leaves')}}</th>
                                <th>{{__('Rejected Leaves')}}</th>
                                <th>{{__('Pending Leaves')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leaves as $leave)

                                <tr>
                                    <td>{{ \Auth::user()->employeeIdFormat($leave['employee_id']) }}</td>
                                    <td>{{$leave['employee']}}</td>

                                    <td>
                                        <a href="#!" data-url="{{ route('report.employee.leave',[$leave['id'],'Approve',isset($_GET['type']) ?$_GET['type']:'no',isset($_GET['month'])?$_GET['month']:date('Y-m'),isset($_GET['year'])?$_GET['year']:date('Y')]) }}" data-ajax-popup="true" data-title="{{__('Approved Leave Detail')}}" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <span class="badge badge-success mr-2">{{$leave['approved']}}</span> <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#!" data-url="{{ route('report.employee.leave',[$leave['id'],'Reject',isset($_GET['type']) ?$_GET['type']:'no',isset($_GET['month'])?$_GET['month']:date('Y-m'),isset($_GET['year'])?$_GET['year']:date('Y')]) }}" class="table-action table-action-delete" data-ajax-popup="true" data-title="{{__('Rejected Leave Detail')}}" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <span class="badge badge-danger mr-2">{{$leave['reject']}}</span> <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#!" data-url="{{ route('report.employee.leave',[$leave['id'],'Pending',isset($_GET['type']) ?$_GET['type']:'no',isset($_GET['month'])?$_GET['month']:date('Y-m'),isset($_GET['year'])?$_GET['year']:date('Y')]) }}" class="table-action table-action-delete" data-ajax-popup="true" data-title="{{__('Pending Leave Detail')}}" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <span class="badge badge-primary mr-2">{{$leave['pending']}}</span> <i class="fas fa-eye"></i>
                                        </a>
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

