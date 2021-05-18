@extends('layouts.admin')
@section('page-title')
    {{__('Lead')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Lead')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Lead')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Lead')}}</h2>
                            </div>
                            <div class="col-auto">
                               <span class="create-btn">
                                    <a href="{{ route('lead.index') }}" class="btn btn-outline-primary btn-sm">{{__('Kanban View')}} </a>
                                </span>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('lead.create') }}" data-ajax-popup="true" data-title="{{__('Create New Lead')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Subject')}}</th>
                                <th>{{__('Stage')}}</th>
                                <th>{{__('Users')}}</th>
                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($leads) > 0)
                                @foreach ($leads as $lead)
                                    <tr>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->subject }}</td>
                                        <td>{{ !empty($lead->stage)?$lead->stage->name:'' }}</td>
                                        <td>
                                            @foreach($lead->users as $user)
                                                <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                    <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                                </a>
                                            @endforeach
                                        </td>
                                        @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                            <td class="text-right">
                                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                                    <a href="{{ route('lead.show',\Crypt::encrypt($lead->id)) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if(\Auth::user()->type=='company')
                                                    <a href="#" data-url="{{ route('lead.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Create New Lead')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$lead->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['lead.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr class="font-style">
                                    <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

