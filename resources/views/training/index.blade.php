@extends('layouts.admin')
@section('page-title')
    {{__('Training')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Training')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Training')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Training')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('training.create') }}" data-ajax-popup="true" data-title="{{__('Create New Training')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Training Type')}}</th>
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Trainer')}}</th>
                                <th>{{__('Training Duration')}}</th>
                                <th>{{__('Cost')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($trainings as $training)
                                <tr>
                                    <td>{{ !empty($training->types)?$training->types->name:'' }} <br>

                                        @if($training->status == 0)
                                            <span class="text-warning">{{ __($status[$training->status]) }}</span>
                                        @elseif($training->status == 1)
                                            <span class="text-primary">{{ __($status[$training->status]) }}</span>
                                        @elseif($training->status == 2)
                                            <span class="text-success">{{ __($status[$training->status]) }}</span>
                                        @elseif($training->status == 3)
                                            <span class="text-info">{{ __($status[$training->status]) }}</span>
                                        @endif

                                    </td>
                                    <td>{{ !empty($training->employees)?$training->employees->name:'' }} </td>
                                    <td>{{ !empty($training->trainers)?$training->trainers->firstname:'' }}</td>
                                    <td>{{\Auth::user()->dateFormat($training->start_date) .' to '.\Auth::user()->dateFormat($training->end_date)}}</td>
                                    <td>{{\Auth::user()->priceFormat($training->training_cost)}}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="text-right">
                                            <a href="{{ route('training.show',\Crypt::encrypt($training->id)) }}" class="table-action"  data-toggle="tooltip" data-original-title="{{__('Show')}}">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="#" class="table-action" data-url="{{ route('training.edit',$training->id) }}" data-ajax-popup="true" data-title="{{__('Edit Training')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$training->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['training.destroy', $training->id],'id'=>'delete-form-'.$training->id]) !!}
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

