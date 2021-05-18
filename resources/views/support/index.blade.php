@extends('layouts.admin')
@section('page-title')
    {{__('Support')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Support')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Support')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Support')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                    <span class="create-btn">
                                    <a href="#" data-url="{{ route('support.create') }}" data-ajax-popup="true" data-title="{{__('Create New Support')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Subject')}}</th>
                                <th>{{__('Support Code')}}</th>
                                <th>{{__('Priority')}}</th>
                                <th>{{__('Attachment')}}</th>
                                <th>{{__('Created By')}}</th>
                                <th>{{__('Created At')}}</th>
                                <th class="text-right">{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($supports as $support)

                                <tr>
                                    <td>{{$support->subject}}</td>
                                    <td>{{$support->ticket_code}}</td>
                                    <td>
                                        @if($support->priority == 0)
                                            <span class="badge badge-primary">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                        @elseif($support->priority == 1)
                                            <span class="badge badge-info">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                        @elseif($support->priority == 2)
                                            <span class="badge badge-warning">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                        @elseif($support->priority == 3)
                                            <span class="badge badge-danger">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($support->attachment))
                                            <a target="_blank" href="{{asset(Storage::url('uploads/supports')).'/'.$support->attachment}}">{{$support->attachment}}</a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{!empty($support->createdBy)?$support->createdBy->name:''}}</td>
                                    <td>{{\Auth::user()->dateFormat($support->created_at)}}</td>
                                    <td class="table-actions text-right">
                                        @if(\Auth::user()->type=='employee')
                                            @if($support->replyUnread()>0)
                                                <i title="New Message" class="fas fa-circle circle"></i>
                                            @endif
                                        @else
                                            @if($support->replyUnread()>0)
                                                <i title="New Message" class="fas fa-circle circle"></i>
                                            @endif
                                        @endif

                                        <a href="{{ route('support.reply',\Crypt::encrypt($support->id)) }}" data-title="{{__('Support Reply')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Reply')}}">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                        @if(\Auth::user()->type=='company')
                                            <a href="#" data-url="{{ route('support.edit',$support->id) }}" data-ajax-popup="true" data-title="{{__('Edit Support')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$support->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['support.destroy', $support->id],'id'=>'delete-form-'.$support->id]) !!}
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

