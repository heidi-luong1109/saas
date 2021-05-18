@extends('layouts.admin')
@section('page-title')
    {{__('Tax Rate')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Tax Rate')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Tax Rate')}}</li>
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
                                <h3 class="mb-0">{{__('Tax Rate')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-6 text-right">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('taxRate.create') }}" data-ajax-popup="true" data-title="{{__('Create New Tax Rate')}}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i>  {{__('Create')}}
                                    </a>
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-striped"  id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th> {{__('Tax Name')}}</th>
                                <th> {{__('Rate %')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($taxes as $tax)
                                <tr class="font-style">
                                    <td>{{ $tax->name }}</td>
                                    <td>{{ $tax->rate }}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td class="action text-right">
                                            <a href="#" data-url="{{ route('taxRate.edit',$tax->id) }}" data-ajax-popup="true" data-title="{{__('Edit Tax Rate')}}" class="table-action" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="far fa-edit"></i>
                                            </a>
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

