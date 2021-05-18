@php
    $logo=asset(Storage::url('uploads/logo/'));
       $company_favicon=Utility::getValByName('company_favicon');
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>@yield('page-title') - {{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'CRMGo')}}</title>
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
    <link href="{{ asset('assets/module/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{asset('assets/vendor/fullcalendar/dist/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.1.0') }} " type="text/css">
    <link href="{{ asset('assets/module/bootstrap-toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>

    @stack('css-page')

    <link rel="stylesheet" href="{{ asset('assets/module/css/custom.css') }} " type="text/css">

</head>
