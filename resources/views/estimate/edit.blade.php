@extends('layouts.admin')
@section('page-title')
    {{__('Estimate Edit')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script src="{{asset('assets/js/jquery.repeater.min.js')}}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {

            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({

                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function () {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        var el = $(this).parent().parent().parent().parent();
                        var id = $(el.find('.id')).val();

                        $.ajax({
                            url: '{{route('estimate.product.destroy')}}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'id': id
                            },
                            cache: false,
                            success: function (data) {

                            },
                        });

                        $(this).slideUp(deleteElement);
                        $(this).remove();
                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function (setIndexes) {

                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    tr.find('.item').val(value[i].item);
                    changeItem(tr.find('.item'));
                }
            }

        }


        $(document).on('change', '.item', function () {
            changeItem($(this));
        });

        var estimate_id = '{{$estimate->id}}';

        function changeItem(element) {
            var items_id = element.val();
            var url = element.data('url');

            var el = element;
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'product_id': items_id
                },
                cache: false,
                success: function (data) {
                    var item = JSON.parse(data);

                    $.ajax({
                        url: '{{route('estimate.items')}}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('#token').val()
                        },
                        data: {
                            'estimate_id': estimate_id,
                            'items_id': items_id,
                        },
                        cache: false,
                        success: function (data) {
                            var estimateItems = JSON.parse(data);

                            if (estimateItems != null) {
                                var amount = (estimateItems.price * estimateItems.quantity);

                                $(el.parent().parent().find('.quantity')).val(estimateItems.quantity);
                                $(el.parent().parent().find('.price')).val(estimateItems.price);
                                $(el.parent().parent().find('.discount')).val(estimateItems.discount);
                            } else {
                                $(el.parent().parent().find('.quantity')).val(1);
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().find('.discount')).val(0);
                            }


                            var taxes = '';
                            var tax = [];

                            var totalItemTaxRate = 0;
                            for (var i = 0; i < item.taxes.length; i++) {
                                taxes += '<span class="badge badge-primary mr-1 mt-1">' + item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                                tax.push(item.taxes[i].id);
                                totalItemTaxRate += parseFloat(item.taxes[i].rate);
                            }

                            if (estimateItems != null) {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (estimateItems.price * estimateItems.quantity));
                            } else {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.sale_price * 1));
                            }


                            $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                            $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                            $(el.parent().parent().find('.taxes')).html(taxes);
                            $(el.parent().parent().find('.tax')).val(tax);
                            $(el.parent().parent().find('.unit')).html(item.unit);


                            if (estimateItems != null) {
                                $(el.parent().parent().find('.amount')).html(amount);
                            } else {
                                $(el.parent().parent().find('.amount')).html(item.totalAmount);
                            }

                            var inputs = $(".amount");
                            var subTotal = 0;
                            for (var i = 0; i < inputs.length; i++) {
                                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                            }
                            $('.subTotal').html(subTotal.toFixed(2));

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }


                            var totalItemPrice = 0;
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                            }

                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(subTotal) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

                        }
                    });


                },
            });
        }


        $(document).on('keyup', '.quantity', function () {
            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();


            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $('.totalTax').html(itemTaxPrice.toFixed(2));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup', '.price', function () {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();

            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })


        $(document).on('keyup', '.discount', function () {
            var el = $(this).parent().parent().parent().parent();
            var discount = $(this).val();
            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();

            var amount = (quantity * price);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (amount));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice);


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice) - parseFloat(totalItemDiscountPrice)).toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

        })


        $(document).on('click', '#discount_apply', function () {
            var checkedValue = $('#discount_apply:checked').val();

            if (checkedValue == 'on') {
                $(".discount-field").removeClass('d-none')
            } else {
                $(".discount-field").addClass('d-none')
            }
        })

        var discount_apply = '{{$estimate->discount_apply}}';
        if (discount_apply > 0) {
            $('#discount_apply').trigger('click');
        }

        $(document).on('click', '[data-repeater-create]', function () {
            $('.item :selected').each(function () {
                var id = $(this).val();

                $(".item option[value='" + id + "']").prop("disabled", true);

                if (discount_apply > 0) {
                    $('#discount_apply').trigger('click');
                }
            });
        })

    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Estimate')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('estimate.index')}}">{{__('Estimate')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Edit')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        {{ Form::model($estimate, array('route' => array('estimate.update', $estimate->id), 'method' => 'PUT')) }}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('client', __('Client')) }}
                                    {{ Form::select('client', $clients,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('issue_date', __('Issue Date')) }}
                                    {{Form::date('issue_date',null,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('expiry_date', __('Expiry Date')) }}
                                    {{Form::date('expiry_date',null,array('class'=>'form-control','required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{ Form::label('category', __('Category')) }}
                                {{ Form::select('category', $categories,null, array('class' => 'form-control custom-select','required'=>'required')) }}
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('estimate_number', __('Estimate Number')) }}
                                    <input type="text" class="form-control" value="{{$estimateId}}" readonly>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card repeater" data-value='{!! json_encode($estimate->items) !!}'>
                    <div class="card-header border-0">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="mb-0">{{__('Item')}}</h3>
                            </div>
                            <div class="col-6 text-right">
                                <a href="#" data-repeater-create="" class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#add-bank">
                                    <i class="fas fa-plus"></i>{{__('Add Item')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Light table -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-striped" data-repeater-list="items" id="sortable-table">
                            <thead class="thead-light">
                            <tr>
                                <th width="20%">{{__('Items')}}</th>
                                <th width="10%">{{__('Quantity')}}</th>
                                <th width="13%">{{__('Price')}}</th>
                                <th>{{__('Tax')}}</th>
                                <th class="discount-field d-none" width="10%">{{__('Discount')}}</th>
                                <th width="17%">{{__('Discription')}}</th>
                                <th width="10%" class="text-right">{{__('Amount')}}</th>
                                <th width="4%"></th>
                            </tr>
                            </thead>
                            <tbody class="ui-sortable">
                            <tr data-repeater-item>
                                {{ Form::hidden('id',null, array('class' => 'form-control id')) }}
                                <td width="25%">
                                    {{ Form::select('item', $items,null, array('class' => 'form-control custom-select item','data-url'=>route('estimate.product'),'required'=>'required')) }}
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            {{ Form::text('quantity',null, array('class' => 'form-control quantity','required'=>'required','placeholder'=>__('Qty'),'required'=>'required')) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            {{ Form::text('price',null, array('class' => 'form-control price','required'=>'required','placeholder'=>__('Price'),'required'=>'required')) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            <div class="taxes"></div>
                                            {{ Form::hidden('tax','', array('class' => 'form-control tax')) }}
                                            {{ Form::hidden('itemTaxPrice','', array('class' => 'form-control itemTaxPrice')) }}
                                            {{ Form::hidden('itemTaxRate','', array('class' => 'form-control itemTaxRate')) }}
                                        </div>
                                    </div>
                                </td>

                                <td class="discount-field d-none">
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            {{ Form::text('discount',null, array('class' => 'form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group colorpickerinput">
                                            {{ Form::text('description',null, array('class' => 'form-control','placeholder'=>__('Description'))) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right amount">
                                    0.00
                                </td>
                                <td>
                                    <a href="#" class="fas fa-trash" data-repeater-delete></a>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox mt-4">
                                            <input class="custom-control-input" type="checkbox" name="discount_apply" id="discount_apply">
                                            <label class="custom-control-label" for="discount_apply">{{__('Discount Apply')}}</label>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="3">&nbsp;</td>
                                <td class="discount-field d-none"></td>
                                <td class="text-right"><strong>{{__('Sub Total')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-right subTotal">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="discount-field d-none"></td>
                                <td class="text-right"><strong>{{__('Discount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-right totalDiscount">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="discount-field d-none"></td>
                                <td class="text-right"><strong>{{__('Tax')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-right totalTax">0.00</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="discount-field d-none"></td>
                                <td class="text-right"><strong>{{__('Total Amount')}} ({{\Auth::user()->currencySymbol()}})</strong></td>
                                <td class="text-right totalAmount">0.00</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>

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

