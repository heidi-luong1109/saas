@extends('layouts.admin')
@php
    @endphp
@section('page-title')
    {{__('Lead Stage')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    @if(\Auth::user()->type=='company')
        <script>
            $(function () {
                $(".sortable").sortable();
                $(".sortable").disableSelection();
                $(".sortable").sortable({
                    stop: function () {
                        var order = [];
                        $(this).find('li').each(function (index, data) {
                            order[index] = $(data).attr('data-id');
                        });

                        $.ajax({
                            url: "{{route('leadStage.order')}}",
                            data: {order: order, _token: $('meta[name="csrf-token"]').attr('content')},
                            type: 'POST',
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                toastr('Error', data.error, 'error')
                            }
                        })
                    }
                });
            });
        </script>
    @endif
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Lead Stage')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Lead Stage')}}</li>
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
                                <ul class="nav nav-tabs" role="tablist">
                                    @php($i=0)
                                    @foreach($pipelines as $key => $pipeline)
                                        <li class="nav-item">
                                            <a class="nav-link @if($i==0) active @endif" data-toggle="tab" href="#tab{{$key}}" role="tab" aria-controls="home" aria-selected="true">{{$pipeline['name']}}</a>
                                        </li>
                                        @php($i++)
                                    @endforeach
                                </ul>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('leadStage.create') }}" data-ajax-popup="true" data-title="{{__('Create New Lead Stage')}}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i>  {{__('Create')}}
                                    </a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content tab-bordered">
                            @php($i=0)
                            @forelse($pipelines as $key => $pipeline)
                                <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel">
                                    <ul class="list-group sortable">
                                        @foreach ($pipeline['lead_stages'] as $lead_stages)
                                            <li class="list-group-item" data-id="{{$lead_stages->id}}">
                                                {{$lead_stages->name}}
                                                @if(\Auth::user()->type=='company')
                                                    <span class="float-right">
                                                    <a href="#" data-url="{{ route('leadStage.edit',$lead_stages->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead Stage')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$lead_stages->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leadStage.destroy', $lead_stages->id],'id'=>'delete-form-'.$lead_stages->id]) !!}
                                                        {!! Form::close() !!}
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @php($i++)
                            @empty
                                <div class="col-md-12 text-center">
                                    <h4>{{__('No data available')}}</h4>
                                </div>
                            @endforelse
                        </div>
                        <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily order change of lead stage using drag & drop.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

