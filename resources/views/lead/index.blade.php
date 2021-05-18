@extends('layouts.admin')
@section('page-title')
    {{__('Lead')}}
@endsection
@push('css-page')
    <style>
        .custom-checkbox .custom-control-input ~ .custom-control-label {
            font-size: .875rem;
            height: unset;
            cursor: pointer;
        }
    </style>
@endpush
@push('script-page')
    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
        @if($pipeline)
            <script src="{{asset('assets/module/js/dragula.min.js')}}"></script>
            <script>
                !function (a) {
                    "use strict";
                    var t = function () {
                        this.$body = a("body")
                    };
                    t.prototype.init = function () {
                        a('[data-plugin="dragula"]').each(function () {
                            var t = a(this).data("containers"), n = [];
                            if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                            var r = a(this).data("handleclass");
                            r ? dragula(n, {
                                moves: function (a, t, n) {
                                    return n.classList.contains(r)
                                }
                            }) : dragula(n).on('drop', function (el, target, source, sibling) {

                                var order = [];
                                $("#" + target.id + " > div").each(function () {
                                    order[$(this).index()] = $(this).attr('data-id');
                                });

                                var id = $(el).attr('data-id');

                                var old_status = $("#" + source.id).data('status');
                                var new_status = $("#" + target.id).data('status');
                                var stage_id = $(target).attr('data-id');
                                var pipeline_id = '{{$pipeline->id}}';

                                $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                                $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                                $.ajax({
                                    url: '{{route('lead.order')}}',
                                    type: 'POST',
                                    data: {lead_id: id, stage_id: stage_id, order: order, new_status: new_status, old_status: old_status, pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                                    success: function (data) {
                                        // console.log(data);
                                    },
                                    error: function (data) {
                                        data = data.responseJSON;
                                        toastrs('Error', data.error, 'error')
                                    }
                                });
                            });
                        })
                    }, a.Dragula = new t, a.Dragula.Constructor = t
                }(window.jQuery), function (a) {
                    "use strict";

                    a.Dragula.init()

                }(window.jQuery);
            </script>
            <script>

                $(document).on("click", ".pipeline_id", function () {
                    var pipeline_id = $(this).attr('data-id');

                    $.ajax({
                        url: '{{route('lead.change.pipeline')}}',
                        type: 'POST',
                        data: {pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                        success: function (data) {
                            $('#change-pipeline').submit();
                            location.reload();
                        }
                    });
                });
            </script>
        @endif
    @endif
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
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Lead')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')

                                @if($pipeline)
                                    <div class="col-md-2 text-right">
                                       <span class="create-btn">
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-outline-primary btn-sm" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="btn-inner--text">{{$pipeline->name}}</span>
                                              </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-160px, 32px, 0px);">
                                                @foreach($pipelines as $pipe)
                                                    <a class="dropdown-item pipeline_id" data-id="{{$pipe->id}}" href="#">{{$pipe->name}}</a>
                                                @endforeach
                                            </div>
                                          </div>
                                        </span>
                                    </div>
                                @endif
                            @endif
                            <div class="col-auto">
                               <span class="create-btn">
                                    <a href="{{ route('lead.list') }}" class="btn btn-outline-primary btn-sm">{{__('List View')}} </a>
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
                    <div class="card-body kanban-body">

                        @if($pipeline)
                            @php
                                $lead_stages = $pipeline->leadStages;

                                $json = [];
                                foreach ($lead_stages as $lead_stage){
                                    $json[] = 'task-list-'.$lead_stage->id;
                                }
                            @endphp

                            <div class="board" data-plugin="dragula" data-containers='{!! json_encode($json) !!}'>
                                <div class="row">
                                    @foreach($lead_stages as $lead_stage)

                                        @php $leads = $lead_stage->lead() @endphp

                                        <div class="col-lg-3">
                                            <div class="lead-head">
                                                <div class="">
                                                    <h4>{{$lead_stage->name}}</h4>
                                                    <span class="badge">{{count($leads)}}</span>
                                                </div>
                                            </div>
                                            <div id="task-list-{{$lead_stage->id}}" data-id="{{$lead_stage->id}}" class="lead-item-body scrollbar-inner">
                                                @foreach($leads as $lead)
                                                    <div class="lead-item card mb-2 mt-0" data-id="{{$lead->id}}">
                                                        <h5><a href="{{ route('lead.show',\Crypt::encrypt($lead->id)) }}">{{$lead->name}}</a></h5>
                                                        <p>{{$lead->subject}}</p>
                                                        <div class="table-actions">
                                                            <div class="dropdown">
                                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 32px, 0px);">
                                                                    @if(!$lead->is_active)
                                                                        <a href="#" class="table-action">
                                                                            <i class="fas fa-lock"></i>
                                                                        </a>
                                                                    @else
                                                                        @if(\Auth::user()->type=='company')
                                                                            <a class="dropdown-item" href="#" data-url="{{ route('lead.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead')}}"> {{__('Edit')}}</a>
                                                                        @endif
                                                                        @if(\Auth::user()->type=='company')
                                                                            <a class="dropdown-item" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$lead->id}}').submit();"> {{__('Delete')}}</a>

                                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['lead.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                            {!! Form::close() !!}
                                                                        @endif
                                                                        @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                                                            <a class="dropdown-item" href="{{ route('lead.show',\Crypt::encrypt($lead->id)) }}"> {{__('View')}}</a>
                                                                        @endif
                                                                        @if(\Auth::user()->type=='company')
                                                                            <a class="dropdown-item" href="#" data-url="{{ route('lead.label',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Label')}}">  {{__('Label')}}</a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <p class="card-text small text-muted">
                                                                    <i class="far fa-clock"></i> {{\Auth::user()->dateFormat($lead->date)}}
                                                                </p>
                                                            </div>

                                                            <div class="col">
                                                                @foreach($lead->users as $user)
                                                                    <a href="#!" data-toggle="tooltip">
                                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar.png')}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 text-center">
                                <h4>{{__('No data available')}}</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

