@extends('layouts.admin')
@section('page-title')
    {{__('Trip')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Trip')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Trip')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Trip')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('trip.create') }}" data-ajax-popup="true" data-title="{{__('Create New Trip')}}" class="btn btn-outline-primary btn-sm">
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
                                @if(\Auth::user()->type=='company')
                                    <th>{{__('Employee Name')}}</th>
                                @endif
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date')}}</th>
                                <th>{{__('Purpose of Visit')}}</th>
                                <th>{{__('Place Of Visit')}}</th>
                                <th>{{__('Description')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($trips as $trip)
                                <tr>
                                    @if(\Auth::user()->type=='company')
                                        <td>{{ !empty($trip->employee)?$trip->employee->name:'' }}</td>
                                    @endif
                                    <td>{{ \Auth::user()->dateFormat( $trip->start_date) }}</td>
                                    <td>{{ \Auth::user()->dateFormat( $trip->end_date) }}</td>
                                    <td>{{ $trip->purpose_of_visit }}</td>
                                    <td>{{ $trip->place_of_visit }}</td>
                                    <td>{{ $trip->description }}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="text-right">
                                            <a href="#" class="table-action" data-url="{{ route('trip.edit',$trip->id) }}" data-ajax-popup="true" data-title="{{__('Edit Trip')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$trip->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['trip.destroy', $trip->id],'id'=>'delete-form-'.$trip->id]) !!}
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

