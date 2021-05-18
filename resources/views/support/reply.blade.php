@extends('layouts.admin')
@section('page-title')
    {{__('Support Reply')}}
@endsection
@php
    $image=asset(Storage::url('uploads/'));
@endphp
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Support Reply')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('support.index')}}">{{__('Support')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Reply')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="#">
                                <img src="{{!empty($support->createdBy)?$image.'/avatar/'.$support->createdBy->avatar:$image.'avatar/avatar.png'}}" class="avatar">
                            </a>
                            <div class="mx-3">
                                <a href="#" class="text-dark font-weight-600 text-sm">{{!empty($support->createdBy)?$support->createdBy->name:''}}</a>
                                <small class="d-block text-muted">{{$support->created_at}}</small>
                                <small class="d-block text-muted">{{$support->ticket_code}}</small>
                            </div>
                        </div>
                        <div class="text-right ml-auto">
                            {{__('Priority').' : '}}
                            @if($support->priority == 0)
                                <span class="badge badge-primary">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                            @elseif($support->priority == 1)
                                <span class="badge badge-info">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                            @elseif($support->priority == 2)
                                <span class="badge badge-warning">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                            @elseif($support->priority == 3)
                                <span class="badge badge-danger">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                            @endif
                        </div>
                        <div class="text-right ml-auto">
                            {{__('Status').' : '}}
                            @if($support->status == 'Open')
                                <span class="badge badge-primary"> {{__('Open')}}</span>
                            @elseif($support->status == 'Close')
                                <span class="badge badge-danger">   {{ __('Closed') }}</span>
                            @elseif($support->priority == 'On Hold')
                                <span class="badge badge-warning">   {{ __('On Hold') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="h5 mt-0">{{__('Subject')}}</h6>
                                <p class="text-sm lh-160">{{$support->subject}}

                                <h6 class="h5 mt-0">{{__('Description')}}</h6>
                                <p class="text-sm lh-160">{{$support->descroption}}
                            </div>
                            @if(!empty($support->attachment))
                                <div class="col-md-6">
                                    <a href="{{$image.'/supports/'.$support->attachment}}" target="_blank">
                                        <img alt="Image placeholder" src="{{$image.'/supports/'.$support->attachment}}" width="300px" class="img-fluid rounded">
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mb-1">
                            @foreach($replyes as $reply)
                                <div class="media media-comment">
                                    <img alt="Image placeholder" class="avatar avatar-lg media-comment-avatar rounded-circle" src="{{!empty($reply->users)?$image.'/avatar/'.$reply->users->avatar:$image.'avatar/avatar.png'}}">
                                    <div class="media-body">
                                        <div class="media-comment-text">
                                            <h6 class="h5 mt-0">{{!empty($reply->users)?$reply->users->name:''}}</h6>
                                            <p class="text-sm lh-160">{{$reply->description}}

                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <div class="media align-items-center">
                                <img alt="Image placeholder" class="avatar avatar-lg rounded-circle mr-4" src="{{!empty(\Auth::user()->avatar)?$image.'/avatar/'.\Auth::user()->avatar:$image.'avatar/avatar.png'}}">
                                <div class="media-body">
                                    {{ Form::open(array('route' => array('support.reply.answer',$support->id))) }}
                                    <div class="row">
                                        <div class="col-md-10">
                                            <textarea class="form-control" placeholder="Write your comment" rows="1" name="description"></textarea>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            {{Form::submit(__('Send'),array('class'=>'btn btn-primary'))}}
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

