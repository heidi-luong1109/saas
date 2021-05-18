@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar'));
@endphp
@section('page-title')
    {{__('Client')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Client')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Client')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Clients')}}</h2>
                            </div>
                            <div class="col-auto">
                                <a href="#" data-url="{{ route('client.create') }}" data-ajax-popup="true" data-title="{{__('Create New Client')}}" class="btn btn-outline-primary btn-sm">
                                    <i class="fa fa-plus"></i> {{__('Create')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Client ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Status')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td><a href="{{route('client.show',\Crypt::encrypt($client->id))}}" class="btn btn-sm btn-primary">{{!empty($client->clientDetail)? \Auth::user()->clientIdFormat($client->clientDetail->client_id):''}}</a></td>
                                    <td class="table-user">
                                        <img src="{{$profile.'/'.$client->avatar}}" class="avatar rounded-circle mr-3 tbl-avatar">
                                        <b>{{$client->name}}</b>
                                    </td>
                                    <td>{{$client->email}}</td>
                                    <td>
                                        @if($client->is_active == 0)
                                            <span class="badge badge-danger">{{ __(\App\Client::$statues[$client->is_active]) }}</span>
                                        @elseif($client->is_active == 1)
                                            <span class="badge badge-success">{{ __(\App\Client::$statues[$client->is_active]) }}</span>
                                        @endif
                                    </td>
                                    <td class="table-actions text-right">
                                        <a href="{{route('client.show',\Crypt::encrypt($client->id))}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('client.edit',$client->id) }}" class="table-action"  data-toggle="tooltip" data-original-title="{{__('Edit')}}" >
                                            <i class="far fa-edit"></i>
                                        </a>

                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$client->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['client.destroy', $client->id],'id'=>'delete-form-'.$client->id]) !!}
                                        {!! Form::close() !!}
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

