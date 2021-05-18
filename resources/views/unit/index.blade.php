@extends('layouts.admin')
@section('page-title')
    {{__('Unit')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Unit')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Unit')}}</li>
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
                                <h3 class="mb-0">{{__('Unit')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('unit.create') }}" data-ajax-popup="true" data-title="{{__('Create New Unit')}}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-striped"  id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th class="text-right"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($units as $unit)
                                <tr class="font-style">
                                    <td>{{ $unit->name }}</td>
                                    <td class="action text-right">
                                        <a href="#" data-url="{{ route('unit.edit',$unit->id) }}" data-ajax-popup="true" data-title="{{__('Edit Unit')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$unit->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['unit.destroy', $unit->id],'id'=>'delete-form-'.$unit->id]) !!}
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

