@extends('layouts.admin')
@section('page-title')
    {{__('Lead Report')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        var BarsChart = (function () {
            var $chart = $('#chart-lead');

            function initChart($chart) {
                var ordersChart = new Chart($chart, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labels) !!},
                        datasets: [{
                            label: 'Lead',
                            data: {!! json_encode($data) !!}
                        }]
                    }
                });
                $chart.data('chart', ordersChart);
            }

            if ($chart.length) {
                initChart($chart);
            }
        })();
    </script>
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
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Lead Report')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Lead Report')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.lead'),'method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('start_month',__('Start Month'))}}
                                {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('end_month',__('End Month'))}}
                                {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('report.lead')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <input type="hidden" value="{{'Lead Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                                <div class="row">
                                    <div class="col">
                                        {{__('Report')}} : <h4>{{__('Lead Summary')}}</h4>
                                    </div>
                                    <div class="col">
                                        {{__('Duration')}} : <h4>{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body min-height">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <!-- Chart wrapper -->
                                <canvas id="chart-lead" class="chart-canvas chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="report-dataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Subject')}}</th>
                                <th>{{__('Stage')}}</th>
                                <th>{{__('Users')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($leads as $lead)
                                <tr>
                                    <td>{{ $lead->name }}</td>
                                    <td>{{ \Auth::user()->dateFormat($lead->date) }}</td>
                                    <td>{{ $lead->subject }}</td>
                                    <td>{{ !empty($lead->stage)?$lead->stage->name:'' }}</td>
                                    <td>
                                        @foreach($lead->users as $user)
                                            <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                            </a>
                                        @endforeach
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

