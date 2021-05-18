@extends('layouts.admin')
@section('page-title')
    {{__('Notice Board')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>
        $(document).on('click', '.type', function () {
            var type = $(this).val();
            if (type == 'Employee') {
                $('.department').addClass('d-block');
                $('.department').removeClass('d-none')
            } else {
                $('.department').addClass('d-none')
                $('.department').removeClass('d-block');
            }
        });
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Notice Board')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Notice Borad')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            @if(\Auth::user()->type=='company')
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            {{ Form::open(array('url' => 'noticeBoard','method'=>'get')) }}
                            <div class="row">
                                <div class="col">
                                    <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                                </div>
                                <div class="col-md-2">
                                    {{Form::label('type',__('To'))}}
                                    <select class="form-control custom-select" name="type">
                                        <option value="0">{{__('All')}}</option>
                                        <option value="{{__('Employee')}}" {{isset($_GET['type']) && $_GET['type']=='Employee'?'selected':''}}>{{__('Employee')}}</option>
                                        <option value="{{__('Client')}}" {{isset($_GET['type']) && $_GET['type']=='Client'?'selected':''}}>{{__('Client')}}</option>
                                    </select>
                                </div>
                                <div class="col-auto apply-btn">
                                    {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                    <a href="{{route('noticeBoard.index')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                                </div>
                            </div>
                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Manage Notice Board')}}</h2>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-auto">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('noticeBoard.create') }}" data-ajax-popup="true" data-title="{{__('Create New Notice')}}" class="btn btn-outline-primary btn-sm">
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
                                <th>{{__('Notice')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('To')}}</th>
                                <th>{{__('Department')}}</th>
                                <th>{{__('Descrition')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($noticeBoards as $noticeBoard)
                                <tr>
                                    <td>{{$noticeBoard->heading}}</td>
                                    <td>{{\Auth::user()->dateFormat($noticeBoard->created_at)}}</td>
                                    <td>{{$noticeBoard->type}}</td>
                                    <td>{{($noticeBoard->type!='Client') ?($noticeBoard->type=='Employee' && !empty($noticeBoard->departments)?$noticeBoard->departments->name:__('All')):'--'}}</td>
                                    <td>{{$noticeBoard->notice_detail}}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="table-actions text-right">
                                            <a href="#" data-url="{{ route('noticeBoard.edit',$noticeBoard->id) }}" data-ajax-popup="true" data-title="{{__('Edit Notice')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$noticeBoard->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['noticeBoard.destroy', $noticeBoard->id],'id'=>'delete-form-'.$noticeBoard->id]) !!}
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

