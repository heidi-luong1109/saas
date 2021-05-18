@extends('layouts.admin')
@section('page-title')
    {{__('Meeting')}}
@endsection
@push('script-page')
    <script>
        var Fullcalendar = (function () {
            var $calendar = $('[data-toggle="calendar"]');
            function init($this) {
                var meetings ={!! $arrMeeting !!},
                    locale = '{{basename(App::getLocale())}}',

                    options = {
                        header: {
                            right: '',
                            center: '',
                            left: ''
                        },
                        buttonIcons: {
                            prev: 'calendar--prev',
                            next: 'calendar--next'
                        },
                        theme: false,
                        selectable: true,
                        selectHelper: true,
                        editable: true,
                        events: meetings,

                        dayClick: function (date) {
                            var isoDate = moment(date).toISOString();
                            $('#new-event').modal('show');
                            $('.new-event--title').val('');
                            $('.new-event--start').val(isoDate);
                            $('.new-event--end').val(isoDate);
                        },

                        viewRender: function (view) {
                            var calendarDate = $this.fullCalendar('getDate');
                            var calendarMonth = calendarDate.month();

                            $('.fullcalendar-title').html(view.title);
                        },

                        eventClick: function (event, element) {
                            $('#edit-event input[value=' + event.className + ']').prop('checked', true);
                            $('#edit-event').modal('show');
                            $('.edit-event--id').val(event.id);
                            $('.edit-event--title').val(event.title);
                            $('.edit-event--description').val(event.description);
                        }
                    };

                // Initalize the calendar plugin
                $this.fullCalendar(options);

                //Calendar views switch
                $('body').on('click', '[data-calendar-view]', function (e) {
                    e.preventDefault();

                    $('[data-calendar-view]').removeClass('active');
                    $(this).addClass('active');

                    var calendarView = $(this).attr('data-calendar-view');
                    $this.fullCalendar('changeView', calendarView);
                });


                //Calendar Next
                $('body').on('click', '.fullcalendar-btn-next', function (e) {
                    e.preventDefault();
                    $this.fullCalendar('next');
                });


                //Calendar Prev
                $('body').on('click', '.fullcalendar-btn-prev', function (e) {
                    e.preventDefault();
                    $this.fullCalendar('prev');
                });
            }
            if ($calendar.length) {
                init($calendar);
            }
        })();
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Meeting')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Meeting')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'meeting','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col">
                                    {{ Form::label('department', __('Department')) }}
                                    {{ Form::select('department', $departments,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control custom-select')) }}
                                </div>
                                <div class="col">
                                    {{ Form::label('designation', __('Designation')) }}
                                    {{ Form::select('designation', $designations,isset($_GET['designation'])?$_GET['designation']:'', array('class' => 'form-control custom-select')) }}
                                </div>
                            @endif
                            <div class="col">
                                {{Form::label('start_date',__('Start Date'))}}
                                {{Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col">
                                {{Form::label('end_date',__('End Date'))}}
                                {{Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('meeting.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <ul class="nav nav-tabs">
                                    <li><a class="active" data-toggle="tab" href="#calendar">{{__('Calendar')}}</a></li>
                                    <li><a class="" data-toggle="tab" href="#list">{{__('List')}}</a></li>
                                </ul>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                <span class="create-btn">
                                        <a href="#" data-url="{{ route('meeting.create') }}" data-ajax-popup="true" data-title="{{__('Create New Meeting')}}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="list" class="tab-pane fade in ">
                            <div class="table-responsive py-4">
                                <table class="table table-flush" id="datatable-basic">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>{{__('title')}}</th>
                                        <th>{{__('Date')}}</th>
                                        <th>{{__('Time')}}</th>
                                        <th>{{__('Department')}}</th>
                                        <th>{{__('Designation')}}</th>
                                        @if(\Auth::user()->type=='company')
                                            <th class="text-right" width="200px">{{__('Action')}}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($meetings as $meeting)
                                        <tr>
                                            <td>{{ $meeting->title }}</td>
                                            <td>{{  \Auth::user()->dateFormat($meeting->date) }}</td>
                                            <td>{{  \Auth::user()->timeFormat($meeting->time) }}</td>
                                            <td>{{ !empty($meeting->departments)?$meeting->departments->name:'All' }}</td>
                                            <td>{{ !empty($meeting->designations)?$meeting->designations->name:'All' }}</td>
                                            @if(\Auth::user()->type=='company')
                                                <td class="text-right">
                                                    <a href="#" class="table-action" data-url="{{ route('meeting.edit',$meeting->id) }}" data-ajax-popup="true" data-title="{{__('Edit Meeting')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$meeting->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['meeting.destroy', $meeting->id],'id'=>'delete-form-'.$meeting->id]) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div  id="calendar" class="tab-pane fade in active show">
                            <div class="header header-dark content__title content__title--calendar">
                                <div class="header header-dark bg-primary  content__title content__title--calendar">
                                    <div class="container-fluid">
                                        <div class="header-body">
                                            <div class="row align-items-center py-4">
                                                <div class="col-lg-6">
                                                    <h6 class="fullcalendar-title h2 text-white d-inline-block mb-0"></h6>

                                                </div>
                                                <div class="col-lg-6 mt-3 mt-lg-0 text-lg-right">
                                                    <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                                                        <i class="fas fa-angle-left"></i>
                                                    </a>
                                                    <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                                                        <i class="fas fa-angle-right"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-calendar">
                                    <div class="card-">
                                        <div class="calendar" data-toggle="calendar" id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection

