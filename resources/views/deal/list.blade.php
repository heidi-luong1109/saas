@extends('layouts.admin')
@section('page-title')
    {{__('Deal')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Deal')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Deal')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col">
                <div class="card">
                    @if(\Auth::user()->type=='company')
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h3>{{__('Total Deals')}}</h3>
                                    <p>{{ $cnt_deal['total'] }}</p>
                                </div>
                                <div class="col">
                                    <h3>{{__('This Month Total Deals')}}</h3>
                                    <p>{{ $cnt_deal['this_month'] }}</p>
                                </div>
                                <div class="col">
                                    <h3>{{__('This Week Total Deals')}}</h3>
                                    <p>{{ $cnt_deal['this_week'] }}</p>
                                </div>
                                <div class="col">
                                    <h3>{{__('Last 30 Days Total Deals')}}</h3>
                                    <p>{{ $cnt_deal['last_30days'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Deal')}}</h2>
                            </div>
                            <div class="col-auto">
                               <span class="create-btn">
                                    <a href="{{ route('deal.index') }}" class="btn btn-outline-primary btn-sm">{{__('Kanban View')}} </a>
                                </span>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('deal.create') }}" data-ajax-popup="true" data-title="{{__('Create New Deal')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Price')}}</th>
                                <th>{{__('Stage')}}</th>
                                <th>{{__('Tasks')}}</th>
                                <th>{{__('Users')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Stage')}}</th>
                                <th>{{__('Tasks')}}</th>
                                <th>{{__('Users')}}</th>
                                @if(\Auth::user()->type=='company' ||  \Auth::user()->type=='employee')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </tfoot>
                            <tbody>
                            @if(count($deals) > 0)
                                @foreach ($deals as $deal)
                                    <tr>
                                        <td>{{ $deal->name }}</td>
                                        <td>{{\Auth::user()->priceFormat($deal->price)}}</td>
                                        <td>{{ !empty($deal->stage)?$deal->stage->name:'' }}</td>
                                        <td>{{count($deal->tasks)}}/{{count($deal->complete_tasks)}}</td>
                                        <td>
                                            @foreach($deal->users as $user)
                                                <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                    <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('storage/uploads/avatar/avatar.png')}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                <a href="{{ route('deal.show',\Crypt::encrypt($deal->id)) }}" class="table-action" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            @if(\Auth::user()->type=='company')
                                                <a href="#" data-url="{{ route('deal.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Create New Deal')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$deal->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deal.destroy', $deal->id],'id'=>'delete-form-'.$deal->id]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
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

