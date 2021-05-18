@extends('layouts.admin')
@section('page-title')
    {{__('Estimate')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Estimate')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Estimate')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => 'estimate','method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('status',__('Status'))}}
                                <select class="form-control custom-select" name="status">
                                    <option value="">{{__('All')}}</option>
                                    @foreach($status as $k=>$val)
                                        <option value="{{$k}}" {{(isset($_GET['start_date']) && $_GET['status']==$k)?'selected':''}}> {{$val}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('start_date',__('Start Date'))}}
                                {{Form::date('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('end_date',__('End Date'))}}
                                {{Form::date('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('estimate.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
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
                                <h2 class="h3 mb-0">{{__('Manage Estimate')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="{{ route('estimate.create') }}" class="btn btn-outline-primary btn-sm">
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
                                <th>#</th>
                                @if(\Auth::user()->type != 'client')
                                    <th>{{__('Client')}}</th>
                                @endif
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Expiry Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($estimates as $estimate)
                                <tr>
                                    <td><a class="btn btn-outline-primary btn-sm" href="{{ route('estimate.show',$estimate->id) }}">{{\Auth::user()->estimateNumberFormat($estimate->estimate)}}</a></td>
                                    @if(\Auth::user()->type != 'client')
                                        <td>{{!empty($estimate->clients)?$estimate->clients->name:''}}</td>
                                    @endif
                                    <td>{{\Auth::user()->dateFormat($estimate->issue_date)}}</td>
                                    <td>{{\Auth::user()->dateFormat($estimate->expiry_date)}}</td>
                                    <td>
                                        @if($estimate->status == 0)
                                            <span class="badge badge-primary">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 1)
                                            <span class="badge badge-info">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 2)
                                            <span class="badge badge-success">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 3)
                                            <span class="badge badge-warning">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                        @elseif($estimate->status == 4)
                                            <span class="badge badge-danger">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                        @endif
                                    </td>
                                    <td class="table-actions text-right">
                                        @if(\Auth::user()->type=='company')
                                            @if($estimate->is_convert==0)
                                                <a href="{{ route('estimate.convert',$estimate->id) }}" class="table-action" data-toggle="tooltip" data-original-title="Convert to Invoice">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                            @endif
                                        @endif
                                        @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                            <a href="{{ route('estimate.show',$estimate->id) }}" class="table-action" data-toggle="tooltip" data-original-title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        @if(\Auth::user()->type=='company')
                                            <a href="{{ route('estimate.edit',$estimate->id) }}" class="table-action" data-toggle="tooltip" data-original-title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('estimate-delete-form-{{$estimate->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['estimate.destroy', $estimate->id],'id'=>'estimate-delete-form-'.$estimate->id]) !!}
                                            {!! Form::close() !!}

                                        @endif
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

