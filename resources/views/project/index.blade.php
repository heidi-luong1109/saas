@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@section('page-title')
    {{__('Project')}}
@endsection
@push('css-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Project')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Project')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mb-0">{{__('Manage Project')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                    <span class="create-btn">
                                        <a href="{{ route('project.create') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse ($projects as $project)
                                @php
                                    $percentages=0;
                                        $total=count($project->tasks);

                                        if($total!=0){
                                            $percentages= $project->completedTask() / ($total /100);
                                        }
                                @endphp
                                <div class="col-lg-6">
                                    <div class="project-wrap">
                                        <div class="project-body">
                                            <div>
                                                <a href="{{route('project.show',\Crypt::encrypt($project->id))}}"><h4>{{$project->title}}</h4></a>
                                                @if($project->status=='not_started')
                                                    <span class="badge badge-primary">{{__('Not Started')}}</span>
                                                @elseif($project->status=='in_progress')
                                                    <span class="badge badge-success">{{__('In Progress')}}</span>
                                                @elseif($project->status=='on_hold')
                                                    <span class="badge badge-info">{{__('On Hold')}}</span>
                                                @elseif($project->status=='canceled')
                                                    <span class="badge badge-danger">{{__('Canceled')}}</span>
                                                @elseif($project->status=='finished')
                                                    <span class="badge badge-warning">{{__('Finished')}}</span>
                                                @endif

                                                <div class="tx-gray-500 small mt-1">{{count($project->tasks)}} {{__('opened tasks')}}, {{$project->completedTask()}} {{__('tasks completed')}}</div>
                                            </div>
                                            @if(\Auth::user()->type=='company')
                                                <div class="project-actions custom-menu-dropdown">
                                                    <a class="dropdown" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        <a href="{{ route('project.edit',\Crypt::encrypt($project->id)) }}" class="dropdown-item">
                                                            <i class="far fa-edit"></i>
                                                            {{__('Edit')}}
                                                        </a>
                                                        <a href="#" class="dropdown-item" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                                                            <i class="fas fa-trash"></i>
                                                            {{__('Delete')}}
                                                        </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['project.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="progress m-0" style="height: 3px;">
                                            <div class="progress-bar bg-primary" style="width: {{$percentages}}%;"></div>
                                        </div>
                                        <div class="progreess-status mt-3">
                                            <strong>{{$percentages}}%</strong>&nbsp;{{__('completed')}}
                                        </div>
                                        <div class="project-text">
                                            {{$project->description}}
                                        </div>
                                        <div class="project-details">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="tx-gray-500 small">{{__('Start Date')}}</div>
                                                    <div class="font-weight-bold">{{\Auth::user()->dateFormat($project->start_date)}}</div>
                                                </div>
                                                <div class="col">
                                                    <div class="tx-gray-500 small">{{__('Due Date')}}</div>
                                                    <div class="font-weight-bold">{{\Auth::user()->dateFormat($project->due_date)}}</div>
                                                </div>
                                                <div class="col">
                                                    <div class="tx-gray-500 small">{{__('Budget')}}</div>
                                                    <div class="font-weight-bold">{{\Auth::user()->priceFormat($project->price)}}</div>
                                                </div>
                                                <div class="col">
                                                    <div class="tx-gray-500 small">{{__('Expense')}}</div>
                                                    <div class="font-weight-bold">{{\Auth::user()->priceFormat($project->totalExpense())}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="project-footer">
                                            <div class="project-footer-item">
                                                <div class="team">
                                                    <div class="tx-gray-500 small mb-2">{{__('Team Member')}}</div>
                                                    <div class="d-flex flex-wrap team-avatar">
                                                        @foreach($project->projectUser() as $projectUser)
                                                            <a href="" class="d-block mr-1 mb-1">
                                                                <img width="30" src="{{(!empty($projectUser->avatar)? $profile.'/'.$projectUser->avatar :  $profile.'/avatar.png')}}" alt="" class="wd-30 rounded-circle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{(!empty($projectUser)?$projectUser->name:'')}}">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @php
                                                    $client=(!empty($project->clients)?$project->clients->avatar:'')
                                                @endphp
                                                <div class="client">
                                                    <div class="tx-gray-500 small mb-2">{{__('Client')}}</div>
                                                    <div class="d-flex flex-wrap team-avatar">
                                                        <a href="" class="d-block mr-1 mb-1">
                                                            <img width="30" src="{{(!empty($client)?  $profile.'/'.$client : $profile.'/avatar.png')}}" alt="" class="wd-30 rounded-circle mg-l--10" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{!empty($project->clients)?$project->clients->name:''}}">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <h4>{{__('No data available')}}</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

