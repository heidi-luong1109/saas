@extends('layouts.admin')
@section('page-title')
    {{__('Client Report')}}
@endsection
@push('css-page')
@endpush
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save();

        }


        $(document).ready(function () {
            var filename = $('#filename').val();
            $('#report-dataTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: filename
                    },
                    {
                        extend: 'pdf',
                        title: filename
                    }, {
                        extend: 'print',
                        title: filename
                    }, {
                        extend: 'csv',
                        title: filename
                    }
                ],
                language: dataTabelLang,
            });
        });


    </script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Client Report')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Client Report')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.client'),'method'=>'get')) }}
                        <div class="row">
                            <div class="col">
                                <h2 class="h3 mb-0">{{__('Filter')}}</h2>
                            </div>
                            <div class="col-md-2">
                                {{Form::label('start_month',__('Start Month'))}}
                                {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{Form::label('end_month',__('End Month'))}}
                                {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'',array('class'=>'form-control'))}}
                            </div>
                            <div class="col-md-2">
                                {{ Form::label('client', __('Client')) }}
                                {{ Form::select('client', $clients,isset($_GET['client'])?$_GET['client']:'', array('class' => 'form-control custom-select')) }}
                            </div>
                            <div class="col-auto apply-btn">
                                {{Form::submit(__('Apply'),array('class'=>'btn btn-outline-primary btn-sm'))}}
                                <a href="{{route('report.client')}}" class="btn btn-outline-danger btn-sm">{{__('Reset')}}</a>
                                <a href="#" onclick="saveAsPDF();" class="btn btn-icon icon-left btn-outline-info pdf-btn btn-sm" id="download-buttons">
                                    {{__('Download')}}
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div id="printableArea">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-wrap">
                            <div class="card-body">
                                <input type="hidden" value="{{$filter['client'].' '.__('Client').' '.'Client Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                                <div class="row">
                                    <div class="col">
                                        {{__('Report')}} : <h4>{{__('Client Summary')}}</h4>
                                    </div>
                                    @if($filter['client']!= __('All'))
                                        <div class="col">
                                            {{__('Client')}} : <h4>{{$filter['client'] }}</h4>
                                        </div>
                                    @endif
                                    <div class="col">
                                        {{__('Duration')}} : <h4>{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Invoice')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalInvoice)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Amount')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalAmount)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Tax')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalTax)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Discount')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalDiscount)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Paid')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalPaid)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">{{__('Total Due')}}</h5>
                                            <span class="h2 font-weight-bold mb-0">{{\Auth::user()->priceFormat($clientTotalDue)}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="report-dataTable">
                            <thead class="thead-light">
                            <tr>
                                <th>{{__('Client')}}</th>
                                <th>{{__('Total Invoice')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Total Tax')}}</th>
                                <th>{{__('Total Discount')}}</th>
                                <th>{{__('Total Paid')}}</th>
                                <th>{{__('Total Due')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clientReport as $client)
                                <tr>
                                    <td>{{$client['client']}}</td>
                                    <td>{{$client['totalInvoice']}}</td>
                                    <td>{{\Auth::user()->priceFormat($client['totalAmount'])}}</td>
                                    <td>{{\Auth::user()->priceFormat($client['totalTax'])}}</td>
                                    <td>{{\Auth::user()->priceFormat($client['totalDiscount'])}}</td>
                                    <td>{{\Auth::user()->priceFormat($client['totalPaid'])}}</td>
                                    <td>{{\Auth::user()->priceFormat($client['totalDue'])}}</td>
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

