<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>{{(Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'CRMGo')}}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/favicon.png'))}}" type="image" sizes="16x16">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link href="{{ asset('assets/module/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
    <!-- Argon CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.1.0')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/module/css/custom.css')}}" type="text/css">
</head>

<body class="bg-default">

<!-- Main content -->
<div class="main-content">
    @yield('content')

</div>

<!-- Core -->
<script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/vendor/js-cookie/js.cookie.js')}}"></script>
<script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
<script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
<!-- Argon JS -->
<script src="{{ asset('assets/js/argon.js?v=1.1.0')}}"></script>
<!-- Demo JS - remove this in your project -->
<script src="{{ asset('assets/js/demo.min.js')}}"></script>
</body>

</html>

