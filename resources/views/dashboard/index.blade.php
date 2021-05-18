@extends('layouts.admin')
@push('script-page')
    <script>


        var PieChart = (function () {

            var $chart = $('#chart-project');

            function init($this) {
                var randomScalingFactor = function () {
                    return Math.round(Math.random() * 100);
                };

                var pieChart = new Chart($this, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($data['projectLabel']) !!},
                        datasets: [{
                            data: {!! json_encode($data['projectData']) !!},
                            backgroundColor: ['#11cdef', '#172b4d', '#5e72e4', '#2dce89', '#fb6340'],
                            label: 'Project'
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

                $this.data('chart', pieChart);

            };

            if ($chart.length) {
                init($chart);
            }

        })();

    </script>
@endpush
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Dashboard')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="#">{{__('Dashboard')}}</a></li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        @if(\Auth::user()->type=='company')
            <div class="row">

                @if($data['pipelines']<=0)
                    <div class="col-3">
                        <div class="alert alert-danger">
                            {{__('Please add constant pipeline.')}} <a href="{{route('pipeline.index')}}"><b class="text-white">{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
                @if($data['leadStages']<=0)
                    <div class="col-3">
                        <div class="alert alert-danger">
                            {{__('Please add constant lead stage.')}} <a href="{{route('leadStage.index')}}"><b class="text-white">{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
                @if($data['dealStages']<=0)
                    <div class="col-3">
                        <div class="alert alert-danger">
                            {{__('Please add constant deal stage.')}} <a href="{{route('dealStage.index')}}"><b class="text-white">{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
                @if($data['projectStages']<=0)
                    <div class="col-3">
                        <div class="alert alert-danger">
                            {{__('Please add constant project stage.')}} <a href="{{route('projectStage.index')}}"><b class="text-white">{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="row">
            @if(\Auth::user()->type=='company')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-primary border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Clients')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalClient']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-info border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Employees')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalEmployee']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-danger border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Projects')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalProject']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="ni ni-money-coins"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-default border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Estimation')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalEstimation']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="ni ni-chart-bar-32"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                <div class="col-xl-3 col-md-6">
                    <div class="card {{(\Auth::user()->type=='company')?'bg-gradient-default':'bg-gradient-primary'}} border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Invoices')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalInvoice']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="ni ni-chart-bar-32"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                <div class="col-xl-3 col-md-6">
                    <div class="card {{(\Auth::user()->type=='employee')?'bg-gradient-primary':'bg-gradient-danger'}} border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Lead')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalLead']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="ni ni-paper-diploma"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-info border-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Deal')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalDeal']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                <i class="ni ni-paper-diploma"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-primary border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('Total Items')}}</h5>
                                            <span class="h2 font-weight-bold mb-0 text-white">{{$data['totalItem']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-badge"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Estimation Overview')}}</h5>
                        </div>
                        <div class="card-body" style="min-height: 402px;">
                            @forelse($data['estimationOverview'] as $estimation)
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            {{$estimation['status']}} <span> {{$estimation['total']}}</span>
                                        </div>
                                        <div class="progress-percentage">
                                            <span>{{$estimation['percentage']}}%</span>
                                        </div>
                                    </div>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar {{$data['estimateOverviewColor'][$estimation['status']]}}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$estimation['percentage']}}%;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <h4>{{__('Estimation record not found')}}</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Invoice Overview')}}</h5>
                        </div>
                        <div class="card-body">
                            @forelse($data['invoiceOverview'] as $invoice)
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            {{$invoice['status']}} <span> {{$invoice['total']}}</span>
                                        </div>
                                        <div class="progress-percentage">
                                            <span>{{$invoice['percentage']}}%</span>
                                        </div>
                                    </div>
                                    <div class="progress progress-xs mb-0">
                                        <div class="progress-bar {{$data['invoiceOverviewColor'][$invoice['status']]}}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$invoice['percentage']}}%;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <h4>{{__('Invoice record not found')}}</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Project Status')}}</h5>
                        </div>
                        <div class="card-body top-payment-box">

                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="chart-project" class="chart-canvas chartjs-render-monitor" width="734" height="350" style="display: block; width: 734px; height: 350px;"></canvas>
                            </div>

                            <div class="project-details" style="margin-top: 15px;">
                                <div class="row">
                                    @forelse($data['projects'] as $project)
                                        <div class="col text-center">
                                            <div class="tx-gray-500 small">{{$project['status']}}</div>
                                            <div class="font-weight-bold">{{$project['percentage'].'%'}}</div>
                                        </div>
                                    @empty
                                        <div class="col-md-12 text-center">
                                            <h4>{{__('Project record not found')}}</h4>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='employee')
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{__('Mark Attendance')}}</h5>
                        </div>
                        <div class="card-body top-payment-box">
                            <center>
                                <div class="row">

                                    <div class="col-md-6 float-right border-right">
                                        {{Form::open(array('route'=>array('employee.attendance'),'method'=>'post'))}}
                                        @if(empty($data['employeeAttendance']) || $data['employeeAttendance']->clock_out != '00:00:00')
                                            {{Form::submit(__('CLOCK IN'),array('class'=>'btn btn-success','name'=>'in','value'=>'0','id'=>'clock_in'))}}
                                        @else
                                            {{Form::submit(__('CLOCK IN'),array('class'=>'btn btn-success disabled','disabled','name'=>'in','value'=>'0','id'=>'clock_in'))}}
                                        @endif
                                        {{Form::close()}}
                                    </div>
                                    <div class="col-md-6 float-left">
                                        @if(!empty($data['employeeAttendance']) && $data['employeeAttendance']->clock_out == '00:00:00')
                                            {{Form::model($data['employeeAttendance'],array('route'=>array('attendance.update',$data['employeeAttendance']->id),'method' => 'PUT')) }}
                                            {{Form::submit(__('CLOCK OUT'),array('class'=>'btn btn-danger','name'=>'out','value'=>'1','id'=>'clock_out'))}}
                                        @else
                                            {{Form::submit(__('CLOCK OUT'),array('class'=>'btn btn-danger disabled','name'=>'out','disabled','value'=>'1','id'=>'clock_out'))}}
                                        @endif
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            @endif
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' )
                <div class="col-xl-6">

                    <div class="card widget-calendar">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('Top Due Payment')}}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body top-payment-box">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['topDueInvoice'] as $invoice)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-8">
                                                <h5 class="mb-0">{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</h5>
                                                <small>{{__('Due Amount')}} {{\Auth::user()->priceFormat($invoice->getDue())}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <small>{{\Auth::user()->dateFormat($invoice->due_date)}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <div class="div-actions">
                                                    <a href="{{route('invoice.show',\Crypt::encrypt($invoice->id))}}">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Payment record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                <div class="{{(\Auth::user()->type == 'client') ? 'col-md-12':'col-md-6'}}">
                    <div class="card widget-calendar">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="h3 mb-0">{{__('Top Due Project')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body min-height">
                            <!-- List group -->
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['topDueProject'] as $project)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-8">
                                                <h5 class="mb-0">{{$project->title}}</h5>
                                                <small> {{$project->dueTask()}} {{__('Task Remain')}} </small>
                                            </div>
                                            <div class="col-xl-2">
                                                <small>{{\Auth::user()->dateFormat($project->due_date)}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <div class="div-actions">
                                                    <a href="{{route('project.show',\Crypt::encrypt($project->id))}}">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Project record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee')
                <div class="col-xl-6">
                    <div class="card widget-calendar">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="h3 mb-0">{{__('Top Due Task')}}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body min-height">
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['topDueTask'] as $topDueTask)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-6">
                                                <h5 class="mb-0">{{$topDueTask->title}}</h5>
                                                <small>{{__('Assign to')}} {{!empty($topDueTask->taskUser)?$topDueTask->taskUser->name  :''}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <small>{{$topDueTask->project_title}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <small>{{\Auth::user()->dateFormat($topDueTask->due_date)}}</small>
                                            </div>
                                            <div class="col-xl-2">
                                                <div class="div-actions">
                                                    <a href="{{route('project.show',\Crypt::encrypt($topDueTask->project_id))}}">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Task record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>

                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company' || \Auth::user()->type == 'employee')
                <div class="col-xl-6">
                    <div class="card widget-calendar">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('Meeting Schedule')}}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body min-height">
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['topMeeting'] as $meeting)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-6">
                                                <h5 class="mb-0">{{$meeting->title}}</h5>
                                                <small>{{$meeting->date.' '.$meeting->time}}</small>
                                            </div>
                                            <div class="col-xl-6 text-right">
                                                <small>{{$meeting->notes}}</small>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Meeting record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company' || \Auth::user()->type == 'employee')
                <div class="col-xl-6">
                    <div class="card widget-calendar">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('This Week Event')}}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body min-height">
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['thisWeekEvent'] as $event)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-2">
                                                <h5 class="mb-0">{{$event->name}}</h5>
                                            </div>
                                            <div class="col-xl-3">
                                                <small>{{$event->start_date.' '.$event->start_time}}</small>
                                            </div>
                                            <div class="col-xl-3">
                                                <small>{{$event->end_date.' '.$event->end_time}}</small>
                                            </div>
                                            <div class="col-xl-4 text-right">
                                                <small>{{$event->description}}</small>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Event record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                <div class="col-xl-12">
                    <div class="card widget-calendar">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('Contracts Expiring Soon')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive py-4">
                            <table class="table table-flush" id="datatable-basic">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{__('Subject')}}</th>
                                    <th> {{__('Client')}}</th>
                                    <th>{{__('Contract Type')}}</th>
                                    <th>{{__('Contract Value')}}</th>
                                    <th>{{__('Start Date')}}</th>
                                    <th>{{__('End Date')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($data['contractExpirySoon'] as $contract)

                                    <tr class="font-style">
                                        <td>{{ $contract->subject}}</td>
                                        <td>{{ !empty($contract->clients)?$contract->clients->name:'' }}</td>
                                        <td>{{ !empty($contract->types)?$contract->types->name:'' }}</td>
                                        <td>{{ \Auth::user()->priceFormat($contract->value) }}</td>
                                        <td>{{  \Auth::user()->dateFormat($contract->start_date )}}</td>
                                        <td>{{  \Auth::user()->dateFormat($contract->end_date )}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company')
                <div class="col-xl-6">
                    <div class="card widget-calendar">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('New Support')}}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body min-height">
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['newTickets'] as $ticket)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-12">
                                                <a href="{{ route('support.reply',\Crypt::encrypt($ticket->id)) }}"><h5 class="mb-0">{{$ticket->subject}}</h5></a>
                                                <small>{{\Auth::user()->dateFormat($ticket->created_at)}}</small>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Support record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company')
                <div class="col-xl-6">
                    <div class="card widget-calendar">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">{{__('New Client')}}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body min-height">
                            <ul class="list-group list-group-flush list my--3">
                                @forelse($data['newClients'] as $client)

                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-xl-2">
                                                <img src="{{asset(Storage::url('uploads/avatar')).'/'.$client->avatar}}" class="avatar rounded-circle mr-3">
                                            </div>
                                            <div class="col-xl-6">
                                                <h5 class="mb-0">{{$client->name}}</h5>
                                                <small>{{\Auth::user()->dateFormat($client->created_at)}}</small>
                                            </div>
                                            <div class="col-xl-4">
                                                <h5 class="mb-0">{{$client->email}}</h5>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('Client record not found')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(\Auth::user()->type=='company')
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{__('Goal')}}</h4>
                        </div>
                        <div class="card-body">

                            @forelse($data['goals'] as $goal)
                                @php

                                    $total= $goal->target($goal->goal_type,$goal->from,$goal->to,$goal->amount)['total'];
                                $percentage=$goal->target($goal->goal_type,$goal->from,$goal->to,$goal->amount)['percentage'];
                                @endphp
                                <div class="col-12">
                                    <div class="card card-statistic-1 card-statistic-2">
                                        <div class="card-wrap">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-2">
                                                    <div class="card-header">
                                                        <h4>{{__('Name')}}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        {{$goal->name}}
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-2">
                                                    <div class="card-header">
                                                        <h4>{{__('Type')}}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        {{ __(\App\Goal::$goalType[$goal->goal_type]) }}
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-3">
                                                    <div class="card-header">
                                                        <h4>{{__('Duration')}}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        {{$goal->from .' To '.$goal->to}}
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-12 col-md-5">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-9">
                                                                {{\Auth::user()->priceFormat($total).' of '. \Auth::user()->priceFormat($goal->amount)}}
                                                            </div>
                                                            <div class="col-auto">
                                                                {{ number_format($percentage, 2, '.', '')}}%
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" style="width:{{number_format($goal->target($goal->goal_type,$goal->from,$goal->to,$goal->amount)['percentage'], 2, '.', '')}}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center">
                                            <h4>{{__('Goal record not found')}}</h4>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection

