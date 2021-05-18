@extends('layouts.admin')
@section('page-title')
    {{__('Termination Type')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Termination Type')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Termination Type')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Termination Type')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('termination-type.create') }}" data-ajax-popup="true" data-title="{{__('Create New Termination Type')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Termination Type')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($terminationtypes as $terminationtype)
                                <tr>
                                    <td>{{ $terminationtype->name }}</td>
                                    <td class="text-right">
                                        <a href="#" class="table-action" data-url="{{ route('termination-type.edit',$terminationtype->id) }}" data-ajax-popup="true" data-title="{{__('Edit Termination Type')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>

                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$terminationtype->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['termination-type.destroy', $terminationtype->id],'id'=>'delete-form-'.$terminationtype->id]) !!}
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

