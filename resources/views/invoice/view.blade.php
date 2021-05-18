@extends('layouts.admin')
@section('page-title')
    {{__('Invoice Detail')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">
            @if($invoice->getDue() > 0 &&  Utility::getValByName('enable_stripe') == 'on' && !empty(Utility::getValByName('stripe_key')) && !empty(Utility::getValByName('stripe_secret')))
        var stripe = Stripe('{{ Utility::getValByName('stripe_key') }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    toastrs('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif


    </script>

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
        $(document).on('change', 'select[name=item]', function () {
            var item_id = $(this).val();
            $.ajax({
                url: '{{route('invoice.items')}}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'item_id': item_id,
                },
                cache: false,
                success: function (data) {
                    var invoiceItems = JSON.parse(data);

                    $('.price').val(invoiceItems.sale_price);
                    $('.quantity').val(invoiceItems.quantity);
                    $('.discount').val(0);

                    var taxes = '';
                    var tax = [];
                    for (var i = 0; i < invoiceItems.taxes.length; i++) {
                        taxes += '<span class="badge badge-primary mr-1 mt-1">' + invoiceItems.taxes[i].name + ' ' + '(' + invoiceItems.taxes[i].rate + '%)' + '</span>';
                    }
                    $('.taxId').val(invoiceItems.tax);
                    $('.tax').html(taxes);


                }
            });
        });

        $(document).on('click', '.type', function () {
            var obj = $(this).val();

            if (obj == 'milestone') {
                $('.milestoneTask').removeClass('d-none');
                $('.milestoneTask').addClass('d-block');
                $('.title').removeClass('d-block');
                $('.title').addClass('d-none');

            } else {
                $('.title').removeClass('d-none');
                $('.title').addClass('d-block');
                $('.milestoneTask').removeClass('d-block');
                $('.milestoneTask').addClass('d-none');
            }
        });

    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Invoice Detail')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">{{__('Invoice')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}</li>
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
                                <h3 class="mb-0">{{__('Invoice')}}</h3>
                            </div>
                            @if(\Auth::user()->type=='company')
                                @if($invoice->status!=0 && $invoice->status!=5)
                                    <div class="col-auto">
                                        <a href="#" data-url="{{route('invoice.create.item',$invoice->id)}}" data-ajax-popup="true" data-title="{{__('Add Item')}}" class="btn btn-outline-primary btn-sm">
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                            <span class="btn-inner--text">{{__('Add Item')}}</span>
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" data-url="{{route('invoice.create.receipt',$invoice->id)}}" data-ajax-popup="true" data-title="{{__('Add Item')}}" class="btn btn-outline-primary btn-sm">
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                            <span class="btn-inner--text">{{__('Add Receipt')}}</span>
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-outline-primary btn-sm">
                                            <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                                            <span class="btn-inner--text">{{__('Resend')}}</span>
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-outline-primary btn-sm">
                                            <span class="btn-inner--icon"><i class="ni ni-money-coins"></i></span>
                                            <span class="btn-inner--text">{{__('Payment Reminder')}}</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-auto">
                                        <a href="{{route('invoice.send',$invoice->id)}}" class="btn btn-outline-primary btn-sm">
                                            <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                                            <span class="btn-inner--text">{{__('Send')}}</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                            <div class="col-auto">
                                <a href="{{route('invoice.pdf',\Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <span class="btn-inner--icon"><i class="fa fa-print"></i></span>
                                    <span class="btn-inner--text">{{__('Print')}}</span>
                                </a>
                            </div>
                            @if(\Auth::user()->type == 'client')
                                @if($invoice->getDue() > 0 && ((Utility::getValByName('enable_stripe') == 'on' && !empty(Utility::getValByName('stripe_key')) && !empty(Utility::getValByName('stripe_secret'))) || (Utility::getValByName('enable_paypal')== 'on' && !empty(Utility::getValByName('paypal_client_id')) && !empty(Utility::getValByName('paypal_secret_key')))))
                                    <div class="text-sm-right">
                                        <a href="#" data-toggle="modal" data-target="#paymentModal" class="btn btn-outline-primary btn-sm" type="button">
                                            <i class="ni ni-money-coins mr-1"></i> {{__('Pay Now')}}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body invoice-details">
                        <div class="row mb-10">
                            <div class="col-12 text-right">{{\Auth::user()->invoicenumberFormat($invoice->invoice_id)}}</div>
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
                                        @if(!empty($invoice->clientDetail))
                                            {{!empty($invoice->clientDetail->company_name)?$invoice->clientDetail->company_name:''}} <br>
                                            {{!empty($invoice->clientDetail->mobile)?$invoice->clientDetail->mobile:''}} <br>
                                            {{!empty($invoice->clientDetail->address_1)?$invoice->clientDetail->address_1:''}} <br>
                                            {{!empty($invoice->clientDetail->city)?$invoice->clientDetail->city.','.$invoice->clientDetail->state:''}}<br>
                                            {{!empty($invoice->clientDetail->zip_code)?$invoice->clientDetail->zip_code:''}}
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">

                            <div class="col-md-3">
                                <div class="tx-gray-500 small">{{__('Status')}}</div>
                                <div class="font-weight-bold">
                                    @if($invoice->status == 0)
                                        <span class="badge badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 1)
                                        <span class="badge badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 2)
                                        <span class="badge badge-default">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 3)
                                        <span class="badge badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 4)
                                        <span class="badge badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 5)
                                        <span class="badge badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3 text-center">
                                <div class="tx-gray-500 small">{{__('Issue Date')}}</div>
                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($invoice->issue_date)}}</div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="tx-gray-500 small">{{__('Due Date')}}</div>
                                <div class="font-weight-bold">{{\Auth::user()->dateFormat($invoice->due_date)}}</div>
                            </div>
                            @if(\Auth::user()->type=='company')
                                <div class="col-md-2">
                                    <select class="form-control custom-select status_change font-style" name="status" data-url="{{route('invoice.status.change',$invoice->id)}}">
                                        @foreach($status as $k=>$val)
                                            <option value="{{$k}}" {{($invoice->status==$k)?'selected':''}}> {{$val}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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
                                <th>{{__('Discount')}}</th>
                                <th class="text-right">{{__('Description')}}</th>
                                <th class="text-right">{{__('Price')}}</th>
                                <th class="text-right" width="4%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{$item->item}} </td>
                                    <td>{{($item->quantity!=0)?$item->quantity:'-'}} </td>
                                    <td>{{\Auth::user()->priceFormat($item->price)}} </td>
                                    <td>
                                        @foreach($item->itemTax as $taxes)
                                            {{$taxes['name']}}  ({{$taxes['rate']}})  {{$taxes['price']}} <br>
                                        @endforeach
                                    </td>
                                    <td>{{\Auth::user()->priceFormat($item->discount)}} </td>
                                    <td class="text-right">{{$item->description}} </td>
                                    <td class="text-right"> {{\Auth::user()->priceFormat(($item->price * $item->quantity))}}</td>
                                    <td>
                                        @if(\Auth::user()->type=='company')
                                            <a href="#" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('item-delete-form-{{$item->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.item.delete', $invoice->id,$item->id],'id'=>'item-delete-form-'.$item->id]) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            <tfoot>
                            <tr>
                                <td><b>{{__('Total')}}</b></td>
                                <td><b>{{($invoice->totalQuantity!=0)?$invoice->totalQuantity:'-'}}</b></td>
                                <td><b>{{\Auth::user()->priceFormat($invoice->totalRate)}}</b></td>
                                <td><b>{{\Auth::user()->priceFormat($invoice->totalTaxPrice)}}</b></td>
                                <td>
                                    <b>{{\Auth::user()->priceFormat($invoice->totalDiscount)}}</b>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                <td class="text-right">{{\Auth::user()->priceFormat($invoice->getSubTotal())}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><b>{{__('Discount')}}</b></td>
                                <td class="text-right">{{\Auth::user()->priceFormat($invoice->getTotalDiscount())}}</td>
                                <td></td>
                            </tr>
                            @if(!empty($invoice->taxesData))
                                @foreach($invoice->taxesData as $taxName => $taxPrice)
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                        <td class="text-right">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr class="text-dark">
                                <td colspan="5"></td>
                                <td class="text-right"><b>{{__('Total')}}</b></td>
                                <td class="text-right">{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><b>{{__('Paid')}}</b></td>
                                <td class="text-right">{{\Auth::user()->priceFormat(($invoice->getTotal()-$invoice->getDue()))}}</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-right"><b>{{__('Due')}}</b></td>
                                <td class="text-right">{{\Auth::user()->priceFormat($invoice->getDue())}}</td>
                            </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mb-0">{{__('Payment History')}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Transaction ID')}}</th>
                                <th>{{__('Payment Date')}}</th>
                                <th>{{__('Payment Method')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__('Note')}}</th>
                                <th class="text-right">{{__('Amount')}}</th>
                                @if(\Auth::user()->type=='company')
                                    <th class="text-right"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{$payment->transaction}} </td>
                                    <td>{{\Auth::user()->dateFormat($payment->date)}} </td>
                                    <td>{{!empty($payment->payments)?$payment->payments->name:''}} </td>
                                    <td>{{$payment->payment_type}} </td>
                                    <td>{{$payment->notes}} </td>
                                    <td class="text-right"> {{\Auth::user()->priceFormat(($payment->amount))}}</td>
                                    @if(\Auth::user()->type=='company')
                                        <td width="5%">
                                            <a href="#" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('payment-delete-form-{{$payment->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.payment.delete', $invoice->id,$payment->id],'id'=>'payment-delete-form-'.$payment->id]) !!}
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
@if(\Auth::user()->type=='client')
    @if($invoice->getDue() > 0)
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if(Utility::getValByName('enable_stripe') == 'on' && Utility::getValByName('enable_paypal') == 'on')
                            <ul class="nav nav-pills  mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="btn btn-outline-primary btn-sm active" data-toggle="tab" href="#stripe-payment" role="tab" aria-controls="stripe" aria-selected="true">{{ __('Stripe') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                                </li>
                            </ul>
                        @endif
                        <div class="tab-content">
                            @if(Utility::getValByName('enable_stripe') == 'on')
                                <div class="tab-pane fade {{ ((Utility::getValByName('enable_stripe') == 'on' && Utility::getValByName('enable_paypal') == 'on') || Utility::getValByName('enable_stripe') == 'on') ? "show active" : "" }}" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                    <form method="post" action="{{ route('client.invoice.payment',$invoice->id) }}" class="require-validation" id="payment-form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                </div>
                                                <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                <img src="{{asset('assets/img/payments/discover.png')}}" height="24" alt="discover-card-img">
                                                <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                <img src="{{asset('assets/img/payments/american express.png')}}" height="24" alt="american-express-card-img">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="card-name-on">{{__('Name on card')}}</label>
                                                    <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element">

                                                </div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <br>
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <button class="btn btn-primary btn-sm" type="submit">{{ __('Make Payment') }}</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            @if(Utility::getValByName('enable_paypal') == 'on')
                                <div class="tab-pane fade {{ (Utility::getValByName('enable_stripe') != 'on' && Utility::getValByName('enable_paypal') == 'on') ? "show active" : "" }}" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                    <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('client.pay.with.paypal',$invoice->id) }}">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount">{{ __('Amount') }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                    <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                    @error('amount')
                                                    <span class="invalid-amount" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <button class="btn btn-primary btn-sm" name="submit" type="submit">{{ __('Make Payment') }}</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
