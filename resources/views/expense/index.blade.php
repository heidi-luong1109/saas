@extends('layouts.admin')
@section('page-title')
    {{__('Expense')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Expense')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Expense')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="col">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h2 class="h3 mb-0">{{__('Manage Expenses')}}</h2>
                                </div>
                                @if(\Auth::user()->type=='company')
                                    <div class="col-auto">
                                        <span class="create-btn">
                                           <a href="#" data-url="{{ route('expense.create') }}" data-ajax-popup="true" data-title="{{__('Create New Expense')}}" class="btn btn-outline-primary btn-sm">
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
                                    <th> {{__('Date')}}</th>
                                    <th> {{__('Amount')}}</th>
                                    <th> {{__('User')}}</th>
                                    <th> {{__('Project')}}</th>
                                    <th> {{__('Attachment')}}</th>
                                    <th> {{__('Description')}}</th>
                                    @if(\Auth::user()->type=='company')
                                        <th class="text-right"> {{__('Action')}}</th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($expenses as $expense)
                                    <tr class="font-style">
                                        <td>{{  Auth::user()->dateFormat($expense->date)}}</td>
                                        <td>{{  Auth::user()->priceFormat($expense->amount)}}</td>
                                        <td>{{  (!empty($expense->users)?$expense->users->name:'')}}</td>
                                        <td>{{  !empty($expense->projects)?$expense->projects->title:''}}</td>
                                        <td>
                                            @if(!empty($expense->attachment))
                                                <a href="{{asset(Storage::url('uploads/attachment/'. $expense->attachment))}}" target="_blank">{{  $expense->attachment}}</a>
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>{{  $expense->description}}</td>
                                        @if(\Auth::user()->type=='company')
                                            <td class="action text-right">
                                                <a href="#" data-url="{{ route('expense.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$expense->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['expense.destroy', $expense->id],'id'=>'delete-form-'.$expense->id]) !!}
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
    </div>
@endsection


