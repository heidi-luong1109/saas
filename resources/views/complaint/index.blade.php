@extends('layouts.admin')
@section('page-title')
    {{__('Complaint')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Complaint')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Complaint')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Complaint')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                <div class="col-auto">
                                    <span class="create-btn">
                                        <a href="#" data-url="{{ route('complaint.create') }}" data-ajax-popup="true" data-title="{{__('Create New Complaint')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Complaint From')}}</th>
                                <th>{{__('Complaint Against')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Complaint Date')}}</th>
                                <th>{{__('Description')}}</th>
                                @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{!empty( $complaint->complaintFrom($complaint->complaint_from))? $complaint->complaintFrom($complaint->complaint_from)->name:'' }}</td>
                                    <td>{{ !empty($complaint->complaintAgainst($complaint->complaint_against))?$complaint->complaintAgainst($complaint->complaint_against)->name:'' }}</td>
                                    <td>{{ $complaint->title }}</td>
                                    <td>{{ \Auth::user()->dateFormat( $complaint->complaint_date) }}</td>
                                    <td>{{ $complaint->description }}</td>
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <td class="text-right">
                                            <a href="#" class="table-action" data-url="{{ route('complaint.edit',$complaint->id) }}" data-ajax-popup="true" data-title="{{__('Edit Complaint')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$complaint->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['complaint.destroy', $complaint->id],'id'=>'delete-form-'.$complaint->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endif
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

