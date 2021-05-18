@extends('layouts.admin')
@section('page-title')
    {{__('Task Report')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        var DoughnutChart = (function () {
            var $chart = $('#chart-doughnut');

            function init($this) {
                var doughnutChart = new Chart($this, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($label) !!},
                        datasets: [{
                            data: {!! json_encode($data) !!},
                            backgroundColor: {!! json_encode($color) !!},
                            label: 'Project Task'
                        }],
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });
                $this.data('chart', doughnutChart);
            };

            if ($chart.length) {
                init($chart);
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
    <h6 class="h2 d-inline-block mb-0">{{__('Task Report')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Task Report')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.task'),'method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('start_date',__('Start Date'))}}
                                {{Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('end_date',__('End Date'))}}
                                {{Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-3">
                                {{ Form::label('project', __('Project')) }}
                                {{ Form::select('project', $projectList,isset($_GET['project'])?$_GET['project']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('employee', __('Employee')) }}
                                {{ Form::select('employee', $employees,isset($_GET['employee'])?$_GET['employee']:'', array('class' => 'form-control custom-select')) }}
                            </div>

                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('report.task')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <input type="hidden" value="{{$filter['project'].' '.__('Project').' '.'Task Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange'].' '.__('of').' '.$filter['employee']}}" id="filename">
                                <div class="row">
                                    <div class="col">
                                        {{__('Report')}} : <h4>{{__('Task Summary')}}</h4>
                                    </div>
                                    @if($filter['project']!= __('All'))
                                        <div class="col">
                                            {{__('Project')}} : <h4>{{$filter['project'] }}</h4>
                                        </div>
                                    @endif
                                    @if($filter['employee']!= __('All'))
                                        <div class="col">
                                            {{__('Employee')}} : <h4>{{$filter['employee'] }}</h4>
                                        </div>
                                    @endif
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
                                <canvas id="chart-doughnut" class="chart-canvas chartjs-render-monitor"></canvas>
                            </div>
                            <div class="project-details" style="margin-top: 15px;">
                                <div class="row">

                                    <div class="col text-center">
                                        <div class="tx-gray-500 small">{{__('Total Task')}}</div>
                                        <div class="font-weight-bold">{{$total}}</div>
                                    </div>
                                    @foreach($stages as $stage)
                                        <div class="col text-center">
                                            <div class="tx-gray-500 small"><span class="stages" style="background-color: {{$stage['color']}}">{{$stage['stage']}}</span></div>
                                            <div class="font-weight-bold">{{$stage['total']}}</div>
                                        </div>
                                    @endforeach
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
                                <th>{{__('Project')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Assign To')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($projects as $project)
                                @foreach($project->tasksFilter() as $task)
                                    <tr>
                                        <td>{{$project->title}}</td>
                                        <td>{{$task->title}}</td>
                                        <td>{{!empty($task->taskUser)?$task->taskUser->name:''}}</td>
                                        <td>{{\Auth::user()->dateFormat($task->start_date)}}</td>
                                        <td>{{\Auth::user()->dateFormat($task->due_date)}}</td>
                                        <td>{{!empty($task->stages)?$task->stages->name:''}}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

