@extends('layouts.admin')
@section('page-title')
    {{__('Training Detail')}}
@endsection


@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Training Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Training Detail')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="employee-detail-wrap">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table class="datatables-demo table table-striped table-bordered ">
                                        <tbody>
                                        <tr>
                                            <th scope="row" style="border-top: 0px;">{{__('Training Type')}}</th>
                                            <td class="text-right">{{ !empty($training->types)?$training->types->name:'' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Trainer')}}</th>
                                            <td class="text-right">{{ !empty($training->trainers)?$training->trainers->firstname:'--' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Training Cost')}}</th>
                                            <td class="text-right">{{\Auth::user()->priceFormat($training->training_cost)}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Start Date')}}</th>
                                            <td class="text-right">{{\Auth::user()->dateFormat($training->start_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('End Date')}}</th>
                                            <td class="text-right">{{\Auth::user()->dateFormat($training->end_date)}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Date')}}</th>
                                            <td class="text-right">{{\Auth::user()->dateFormat($training->created_at)}}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <div class="bs-callout-success callout-border-left callout-square callout-transparent mt-3 p-1 text-justify"> {{$training->description}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="employee-detail-wrap">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{__('Training Employee')}}</h4>
                                    <div class="media-list" id="all_employees_list">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item" style="border:0px;">

                                                <div class="media align-items-center">
                                                    <img src="{{!empty($training->employees)? !empty($training->employees->user->avatar)?asset(Storage::url('uploads/avatar')).'/'.$training->employees->user->avatar:asset(Storage::url('uploads/avatar')).'/avatar.png':asset(Storage::url('uploads/avatar')).'/avatar.png'}}" class="user-image-hr-prj ui-w-30 rounded-circle" width="50px" height="50px">
                                                    <div class="media-body px-2">
                                                        <a href="{{route('employee.show',!empty($training->employees)?\Illuminate\Support\Facades\Crypt::encrypt($training->employees->id):0)}}" class="text-dark">
                                                            {{ !empty($training->employees)?$training->employees->name:'' }}
                                                        </a>
                                                        <br>
                                                        {{ !empty($training->employees)?!empty($training->employees->designation)?$training->employees->designation->name:'':'' }}
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{Form::model($training,array('route' => array('training.status', $training->id), 'method' => 'PUT')) }}
                                    <h4>{{__('Update Status')}}</h4>
                                    <div class="col-md-12 mt-4">
                                        <input type="hidden" value="{{$training->id}}" name="id">
                                        <div class="form-group">
                                            {{Form::label('performance',__('Performance'))}}
                                            {{Form::select('performance',$performance,null,array('class'=>'form-control custom-select'))}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{Form::label('status',__('Status'))}}
                                            {{Form::select('status',$status,null,array('class'=>'form-control custom-select'))}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{Form::label('remarks',__('Remarks'))}}
                                            {{Form::textarea('remarks',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Remarks')))}}
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 text-right">
                                        {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

