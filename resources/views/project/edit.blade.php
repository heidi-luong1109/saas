@extends('layouts.admin')
@section('page-title')
    {{__('Project Edit')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Project')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('project.index')}}">{{__('Project')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        {{ Form::model($project, array('route' => array('project.update', $project->id), 'method' => 'PUT')) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('title', __('Project Title')) }}
                                {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('category', __('Category')) }}
                                {{ Form::select('category', $categories,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('price', __('Price')) }}
                                {{ Form::number('price', null, array('class' => 'form-control','required'=>'required','stage'=>'0.01')) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('start_date', __('Start Date')) }}
                                {{Form::date('start_date',null,array('class'=>'form-control','required'=>'required'))}}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('due_date', __('Due Date')) }}
                                {{Form::date('due_date',null,array('class'=>'form-control','required'=>'required'))}}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('lead', __('Lead')) }}
                                {{ Form::select('lead', $leads,null, array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('client', __('Client')) }}
                                {{ Form::select('client', $clients,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('status', __('Status')) }}
                                {{ Form::select('status', $projectStatus,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                            </div>
                            <div class="form-group col-md-12">
                                {{ Form::label('description', __('Description')) }}
                                {{ Form::textarea('description',null, array('class' => 'form-control','rows'=>'3')) }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right">
                <a href="{{route('estimate.index')}}" class="btn btn-secondary">{{__('Cancel')}}</a>
                {{Form::submit(__('Update'),array('class'=>'btn btn-primary'))}}
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection

