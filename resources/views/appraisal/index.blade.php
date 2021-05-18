@extends('layouts.admin')
@section('page-title')
    {{__('Appraisal')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Appraisal')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Appraisal')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Appraisal')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('appraisal.create') }}" data-ajax-popup="true" data-title="{{__('Create New Appraisal')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Department')}}</th>
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Employee')}}</th>
                                <th>{{__('Appraisal Date')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($appraisals as $appraisal)
                                <tr>
                                    <td>{{ !empty($appraisal->employees)?!empty($appraisal->employees->departments)?$appraisal->employees->departments->name:'':'' }}</td>
                                    <td>{{ !empty($appraisal->employees)?!empty($appraisal->employees->designations)?$appraisal->employees->designations->name:'':'' }}</td>
                                    <td>{{!empty($appraisal->user)?$appraisal->user->name:'' }}</td>
                                    <td>{{ \Auth::user()->dateFormat($appraisal->appraisal_date)}}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="text-right">
                                            <a href="#" class="table-action" data-url="{{ route('appraisal.show',$appraisal->id) }}" data-ajax-popup="true" data-title="{{__('Appraisal Detail')}}" data-toggle="tooltip" data-original-title="{{__('Show')}}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="table-action" data-url="{{ route('appraisal.edit',$appraisal->id) }}" data-ajax-popup="true" data-title="{{__('Edit Appraisal')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$appraisal->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['appraisal.destroy', $appraisal->id],'id'=>'delete-form-'.$appraisal->id]) !!}
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

