@extends('layouts.admin')
@section('page-title')
    {{__('Promotion')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Promotion')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Promotion')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Promotion')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('promotion.create') }}" data-ajax-popup="true" data-title="{{__('Create New Promotion')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Designation')}}</th>
                                <th>{{__('Promotion Title')}}</th>
                                <th>{{__('Promotion Date')}}</th>
                                <th>{{__('Description')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($promotions as $promotion)
                                    <tr>
                                        @if(\Auth::user()->type=='company')
                                            <td>{{ !empty($promotion->employee)?$promotion->employee->name:'' }}</td>
                                        @endif
                                        <td>{{ !empty($promotion->designation)?$promotion->designation->name:'' }}</td>
                                        <td>{{ $promotion->promotion_title }}</td>
                                        <td>{{  \Auth::user()->dateFormat($promotion->promotion_date) }}</td>
                                        <td>{{ $promotion->description }}</td>
                                        @if(\Auth::user()->type=='company')
                                            <td class="text-right">
                                                <a href="#" class="table-action" data-url="{{ route('promotion.edit',$promotion->id) }}" data-ajax-popup="true" data-title="{{__('Edit Promotion')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="far fa-edit"></i>
                                                </a>

                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$promotion->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['promotion.destroy', $promotion->id],'id'=>'delete-form-'.$promotion->id]) !!}
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

