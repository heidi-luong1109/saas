@extends('layouts.admin')
@section('page-title')
    {{__('Estimate Detail')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script>

        $(document).on('change', '.status_change', function () {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                    location.reload();
                },
            });
        });
    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Estimate Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('estimate.index')}}">{{__('Estimate')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{\Auth::user()->estimatenumberFormat($estimate->estimate)}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="mb-0">{{__('Estimate')}}</h3>
                            </div>
                            @if($estimate->status==0)
                                <div class="col-auto">
                                    <a href="{{route('estimate.send',$estimate->id)}}" class="btn btn-outline-primary btn-sm">
                                        <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                                        <span class="btn-inner--text">{{__('Send')}}</span>
                                    </a>
                                </div>
                            @else

                                <div class="col-auto">
                                    <a href="{{route('estimate.send',$estimate->id)}}" class="btn btn-outline-primary btn-sm">
                                        <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                                        <span class="btn-inner--text">{{__('Resend')}}</span>
                                    </a>
                                </div>
                            @endif
                            <div class="col-auto">
                                <a href="{{route('estimate.pdf',\Crypt::encrypt($estimate->id))}}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <span class="btn-inner--icon"><i class="fa fa-print"></i></span>
                                    <span class="btn-inner--text">{{__('Print')}}</span>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body invoice-details">
                        <div class="row mb-10">
                            <div class="col-12 text-right">{{\Auth::user()->estimatenumberFormat($estimate->estimate)}}</div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="shipped-t">
                                    <address>
                                        <strong>{{__('From')}} :</strong> <br>
                                        {{$settings['company_name']}}<br>
                                        {{$settings['company_address']}}<br>
                                        {{$settings['company_city']}}
                                        @if(isset($settings['company_city']) && !empty($settings['company_city'])), @endif
                                        {{$settings['company_state']}}
                                        @if(isset($settings['company_zipcode']) && !empty($settings['company_zipcode']))-@endif {{$settings['company_zipcode']}}<br>
                                        {{$settings['company_country']}}
                                    </address>
                                </div>
                            </div>

                            <div class="col-6 text-right">
                                <div class="company-address">
                                    <address>
                                        <strong>{{__('To')}} :</strong> <br>
                                        @if(!empty($estimate->clientDetail))
                                            {{!empty($estimate->clientDetail->company_name)?$estimate->clientDetail->company_name:''}} <br>
                                            {{!empty($estimate->clientDetail->mobile)?$estimate->clientDetail->mobile:''}} <br>
                                            {{!empty($estimate->clientDetail->address_1)?$estimate->clientDetail->address_1:''}} <br>
                                            {{!empty($estimate->clientDetail->city)?$estimate->clientDetail->city.','.$estimate->clientDetail->state:''}}<br>
                                            {{!empty($estimate->clientDetail->zip_code)?$estimate->clientDetail->zip_code:''}}
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="tx-gray-500 small">{{__('Status')}}</div>
                                <div class="font-weight-bold">
                                    @if($estimate->status == 0)
                                        <span class="badge badge-primary">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 1)
                                        <span class="badge badge-info">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 2)
                                        <span class="badge badge-success">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 3)
                                        <span class="badge badge-warning">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 4)
                                        <span class="badge badge-danger">{{ __(\App\Estimate::$statues[$estimate->status]) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="tx-gray-500 small">{{__('Issue Date')}}</div>
                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($estimate->issue_date)}}</div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="tx-gray-500 small">{{__('Expiry Date')}}</div>
                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($estimate->expiry_date)}}</div>
                            </div>
                            <div class="col-md-2 text-right">
                                <select class="form-control custom-select status_change font-style" name="status" data-url="{{route('estimate.status.change',$estimate->id)}}">
                                    @foreach($status as $k=>$val)
                                        <option value="{{$k}}" {{($estimate->status==$k)?'selected':''}}> {{$val}} </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mb-0">{{__('Order Summary')}}</h3>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Item')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Rate')}}</th>
                                <th>{{__('Tax')}}</th>
                                @if($estimate->discount_apply==1)
                                    <th>{{__('Discount')}}</th>
                                @endif
                                <th class="text-right">{{__('Description')}}</th>
                                <th class="text-right">{{__('Price')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $totalQuantity=0;
                               $totalRate=0;
                               $totalAmount=0;
                               $totalTaxPrice=0;
                               $totalDiscount=0;
                               $taxesData=[];
                            @endphp
                            @foreach($estimate->items as $item)
                                @php
                                    $taxes=\Utility::tax($item->tax);
                                    $totalQuantity+=$item->quantity;
                                    $totalRate+=$item->price;
                                    $totalDiscount+=$item->discount;
                                    foreach($taxes as $taxe){
                                        $taxDataPrice=\Utility::taxRate($taxe->rate,$item->price,$item->quantity);
                                        if (array_key_exists($taxe->name,$taxesData))
                                        {
                                            $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                        }
                                        else
                                        {
                                            $taxesData[$taxe->name] = $taxDataPrice;
                                        }
                                    }

                                @endphp

                                <tr>
                                    <td>{{!empty($item->items)?$item->items->name:''}} </td>
                                    <td>{{$item->quantity}} </td>
                                    <td>{{\Auth::user()->priceFormat($item->price)}} </td>
                                    <td>
                                        <table>
                                            @php $totalTaxRate = 0;@endphp
                                            @foreach($taxes as $tax)
                                                @php
                                                    $taxPrice=\Utility::taxRate($tax->rate,$item->price,$item->quantity);
                                                    $totalTaxPrice+=$taxPrice;

                                                @endphp
                                                <tr>
                                                    <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                    <td>{{\Auth::user()->priceFormat($taxPrice)}}</td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </td>

                                    @if($estimate->discount_apply==1)
                                        <td>{{\Auth::user()->priceFormat($item->discount)}} </td>
                                    @endif
                                    <td class="text-right">{{$item->description}} </td>
                                    <td class="text-right"> {{\Auth::user()->priceFormat(($item->price*$item->quantity))}}</td>
                                    @php
                                        $totalQuantity+=$item->quantity;
                                        $totalRate+=$item->price;
                                        $totalDiscount+=$item->discount;
                                        $totalAmount+=($item->price*$item->quantity);
                                    @endphp
                                </tr>
                            @endforeach
                            <tfoot>
                            <tr>
                                <td colspan="4">&nbsp;</td>
                                @if($estimate->discount_apply==1)
                                    <td></td>
                                @endif
                                <td class="text-right"><strong>{{__('Sub Total')}}</strong></td>
                                <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimate->getSubTotal())}}</td>
                            </tr>
                            @if($estimate->discount_apply==1)
                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                    @if($estimate->discount_apply==1)
                                        <td></td>
                                    @endif
                                    <td class="text-right"><strong>{{__('Discount')}}</strong></td>
                                    <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimate->getTotalDiscount())}}</td>
                                </tr>
                            @endif
                            @if(!empty($taxesData))
                                @foreach($taxesData as $taxName => $taxPrice)
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                        <td class="text-right">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="4">&nbsp;</td>
                                @if($estimate->discount_apply==1)
                                    <td></td>
                                @endif
                                <td class="text-right"><strong>{{__('Total')}}</strong></td>
                                <td class="text-right subTotal">{{\Auth::user()->priceFormat($estimate->getTotal())}}</td>
                            </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

