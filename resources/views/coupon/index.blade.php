@extends('layouts.admin')
@section('page-title')
    {{__('Coupon')}}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.code', function () {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Coupon')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Coupon')}}</li>
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
                                <h2 class="h3 mb-0">{{__('Manage Coupons')}}</h2>
                            </div>
                            <div class="col-auto">
                                <span class="create-btn">
                                    <a href="#" data-url="{{ route('coupon.create') }}" data-ajax-popup="true" data-title="{{__('Create New Coupon')}}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i>  {{__('Create')}}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Code')}}</th>
                                <th> {{__('Discount (%)')}}</th>
                                <th> {{__('Limit')}}</th>
                                <th> {{__('Used')}}</th>
                                <th class="text-right"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($coupons as $coupon)

                                <tr class="font-style">
                                    <td>{{ $coupon->name }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount }}</td>
                                    <td>{{ $coupon->limit }}</td>
                                    <td>{{ $coupon->used_coupon() }}</td>
                                    <td class="action text-right">

                                        <a href="{{ route('coupon.show',$coupon->id) }}" class="table-action">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="#!" class="table-action" data-url="{{ route('coupon.edit',$coupon->id) }}" data-ajax-popup="true" data-title="{{__('Edit Goal')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$coupon->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['coupon.destroy', $coupon->id],'id'=>'delete-form-'.$coupon->id]) !!}
                                        {!! Form::close() !!}

                                    </td>
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

