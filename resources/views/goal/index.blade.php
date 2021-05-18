@extends('layouts.admin')
@section('page-title')
    {{__('Goal')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Goal')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Goal')}}</li>
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
                                <h3 class="mb-0">{{__('Manage Goals')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('goal.create') }}" data-ajax-popup="true" data-title="{{__('Create New Goal')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Name')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('From')}} Date</th>
                                <th>{{__('To')}} Date</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Display on dashboard')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($goals as $goal)
                                <tr>
                                    <td>{{$goal->name}}</td>
                                    <td>{{ __(\App\Goal::$goalType[$goal->goal_type]) }}</td>
                                    <td>{{$goal->from}}</td>
                                    <td>{{$goal->to}}</td>
                                    <td>{{\Auth::user()->dateFormat($goal->amount)}}</td>
                                    <td>{{$goal->display==1 ? __('Yes') :__('No')}}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="table-actions text-right ">
                                            <a href="#!" class="table-action" data-url="{{ route('goal.edit',$goal->id) }}" data-ajax-popup="true" data-title="{{__('Edit Goal')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$goal->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['goal.destroy', $goal->id],'id'=>'delete-form-'.$goal->id]) !!}
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

