@extends('layouts.admin')
@section('page-title')
    {{__('Department')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Department')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Department')}}</li>
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
                                <h3 class="mb-0">{{__('Department')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('department.create') }}" data-ajax-popup="true" data-title="{{__('Create New Department')}}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i>  {{__('Create')}}
                                    </a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-striped" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Name')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($departments as $department)
                                <tr>
                                    <td>{{$department->name}} </td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="table-actions text-right">
                                            <a href="#!" class="table-action" data-toggle="tooltip" data-original-title="Edit" data-url="{{ route('department.edit',$department->id) }}" data-ajax-popup="true" data-title="{{__('Edit Department')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$department->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['department.destroy', $department->id],'id'=>'delete-form-'.$department->id]) !!}
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

