@extends('layouts.admin')
@section('page-title')
    {{__('Project Stage')}}
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
                            url: "{{route('projectStage.order')}}",
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
    <h6 class="h2 d-inline-block mb-0">{{__('Project Stage')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Project Stage')}}</li>
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
                                <h3 class="mb-0">{{__('Project Stage')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('projectStage.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project Stage')}}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-plus"></i>  {{__('Create')}}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-pane fade show" id="tab" role="tabpanel">
                            <ul class="list-group sortable">
                                @forelse ($projectStages as $project_stages)
                                    <li class="list-group-item" data-id="{{$project_stages->id}}">
                                        {{$project_stages->name}}
                                        @if(\Auth::user()->type=='company')
                                            <span class="float-right">
                                            <a href="#" data-url="{{ route('projectStage.edit',$project_stages->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project Stage')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project_stages->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['projectStage.destroy', $project_stages->id],'id'=>'delete-form-'.$project_stages->id]) !!}
                                                {!! Form::close() !!}
                                        </span>
                                        @endif
                                    </li>
                                @empty
                                    <div class="col-md-12 text-center">
                                        <h4>{{__('No data available')}}</h4>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                        <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily order change of project stage using drag & drop.')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

