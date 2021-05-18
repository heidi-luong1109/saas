@extends('layouts.admin')
@section('page-title')
    {{__('Note')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Note')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Note')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Note')}}</h2>
                            </div>
                            @if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
                                <div class="col-auto">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('note.create') }}" data-ajax-popup="true" data-title="{{__('Create New Note')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Created Date')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Files')}}</th>
                                <th>{{__('Descrition')}}</th>
                                @if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($notes as $note)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($note->created_at)}}</td>
                                    <td>{{$note->title}}</td>
                                    <td>
                                        @if(!empty($note->file))
                                            <a target="_blank" href="{{asset(Storage::url('uploads/notes')).'/'.$note->file}}">{{$note->file}}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{$note->description}}</td>
                                    @if(\Auth::user()->type == 'company' || \Auth::user()->type == 'employee' || \Auth::user()->type == 'client')
                                        <td class="table-actions text-right">
                                            <a href="#" data-url="{{ route('note.edit',$note->id) }}" data-ajax-popup="true" data-title="{{__('Edit Note')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$note->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['note.destroy', $note->id],'id'=>'delete-form-'.$note->id]) !!}
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

