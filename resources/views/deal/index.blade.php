@extends('layouts.admin')
@section('page-title')
    {{__('Deal')}}
@endsection
@push('css-page')

@endpush
@push('script-page')
    @if(\Auth::user()->type=='company')
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
                                    url: '{{route('deal.order')}}',
                                    type: 'POST',
                                    data: {deal_id: id, stage_id: stage_id, order: order, new_status: new_status, old_status: old_status, pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                                    success: function (data) {
                                        // console.log(data);
                                    },
                                    error: function (data) {
                                        data = data.responseJSON;
                                        toastr('Error', data.error, 'error')
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
        @endif
    @endif
    <script>

        $(document).on("click", ".pipeline_id", function () {
            var pipeline_id = $(this).attr('data-id');
            $.ajax({
                url: '{{route('deal.change.pipeline')}}',
                type: 'POST',
                data: {pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#change-pipeline').submit();
                    location.reload();
                }
            });
        });
    </script>

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
            <div class="col-lg-12">
                @if($pipeline)
                    @if(\Auth::user()->type=='company')
                        <div class="card">
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
                        </div>
                    @endif
                @endif
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h2 class="h3 mb-0">{{__('Manage Deal')}}</h2>
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
                                                    @foreach($pipelines as $id=> $pipe)
                                                            <a class="dropdown-item pipeline_id" data-id="{{$id}}" href="#">{{$pipe}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </span>
                                        </div>
                                    @endif
                                @endif
                                <div class="col-auto">
                               <span class="create-btn">
                                    <a href="{{ route('deal.list') }}" class="btn btn-outline-primary btn-sm">{{__('List View')}} </a>
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
                        <div class="card-body kanban-body">
                            @if($pipeline)
                                @php
                                    $stages = $pipeline->stages;
                                    $json = [];
                                    foreach ($stages as $stage){
                                        $json[] = 'task-list-'.$stage->id;
                                    }
                                @endphp
                                <div class="board" data-plugin="dragula" data-containers='{!! json_encode($json) !!}'>
                                    <div class="row">
                                        @foreach($stages as $stage)

                                            @php $deals = $stage->deals() @endphp
                                            <div class="col-lg-3">
                                                <div class="lead-head">
                                                    <div class="">
                                                        <h4>{{$stage->name}}</h4>
                                                        <span class="badge">{{count($deals)}}</span>
                                                    </div>
                                                </div>
                                                <div id="task-list-{{$stage->id}}" data-id="{{$stage->id}}" class="lead-item-body scrollbar-inner">
                                                    @foreach($deals as $deal)
                                                        <div class="lead-item card mb-2 mt-0" data-id="{{$deal->id}}">

                                                            <h5><a href="{{ route('deal.show',\Crypt::encrypt($deal->id)) }}">{{$deal->name}}</a></h5>

                                                            <p><b>{{count($deal->tasks)}}/{{count($deal->complete_tasks)}}</b> {{__('Tasks')}}</p>

                                                            <div class="table-actions">
                                                                <div class="dropdown">
                                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-v"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 32px, 0px);">
                                                                        @if(\Auth::user()->type=='company')
                                                                            <a class="dropdown-item" href="#" data-url="{{ route('deal.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Edit Deal')}}"> {{__('Edit')}}</a>
                                                                        @endif
                                                                        @if(\Auth::user()->type=='company')
                                                                            <a class="dropdown-item" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$deal->id}}').submit();"> {{__('Delete')}}</a>

                                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['deal.destroy', $deal->id],'id'=>'delete-form-'.$deal->id]) !!}
                                                                            {!! Form::close() !!}
                                                                        @endif
                                                                        @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                                                            <a class="dropdown-item" href="{{ route('deal.show',\Crypt::encrypt($deal->id)) }}"> {{__('View')}}</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col">
                                                                    @foreach($deal->users as $user)
                                                                        <a href="#!" data-toggle="tooltip" class="float-left">
                                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar.png')}}" @endif class="rounded-circle profile-widget-picture" width="25">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                                <div class="col-auto">
                                                                    <p class="card-text small text-muted">
                                                                        {{\Auth::user()->priceFormat($deal->price)}}
                                                                    </p>
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

