@extends('layouts.admin')
@push('script-page')
    <script>
        var SalesChart = (function () {
            var $chart = $('#chart-sales');

            function init($this) {
                var salesChart = new Chart($this, {
                    type: 'line',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    color: Charts.colors.gray[700],
                                    zeroLineColor: Charts.colors.gray[700]
                                },
                                ticks: {}
                            }]
                        }
                    },
                    data: {
                        labels: {!! json_encode($chartData['label']) !!},
                        datasets: [{
                            label: "{{__('Order')}}",
                            data: {!! json_encode($chartData['data']) !!}
                        }]
                    }
                });

                $this.data('chart', salesChart);

            };

            if ($chart.length) {
                init($chart);
            }

        })();
    </script>
@endpush
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Dashboard')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="#">{{__('Dashboard')}}</a></li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 col-md-4">
                <div class="card bg-gradient-primary border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('TOTAL USERS')}}</h5>
                                        <span class="h2 font-weight-bold mb-0 text-white">{{$user->total_user}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-single-02"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mb-0">
                                </div>
                                <div class="progreess-status mt-2">
                                    <span>{{__('PAID USERS')}} :</span>
                                    <span>{{$user['total_paid_user']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card bg-gradient-info border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('TOTAL ORDERS')}}</h5>
                                        <span class="h2 font-weight-bold mb-0 text-white">{{$user->total_orders}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mb-0">
                                </div>
                                <div class="progreess-status mt-2">
                                    <span>{{__('TOTAL ORDER AMOUNT')}} :</span>
                                    <span>{{\Auth::user()->priceFormat($user['total_orders_price'])}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="card bg-gradient-danger border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">{{__('TOTAL PLANS')}}</h5>
                                        <span class="h2 font-weight-bold mb-0 text-white">{{$user['total_plan']}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                            <i class="ni ni-money-coins"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mb-0">
                                </div>
                                <div class="progreess-status mt-2">
                                    <span>{{__('MOST PURCHASE PLAN')}} :</span>
                                    <span>{{$user['most_purchese_plan']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card bg-default">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="h3 text-white mb-0">{{__('Recent Order')}}</h5>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="chart-sales" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

