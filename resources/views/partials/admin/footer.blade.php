<script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js')}}"></script>
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }} "></script>
<script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>

<script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }} "></script>
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }} "></script>
<script src="{{ asset('assets/vendor/jvectormap-next/jquery-jvectormap.min.js') }} "></script>
<script src="{{ asset('assets/js/vendor/jvectormap/jquery-jvectormap-world-mill.js') }} "></script>


<script src="{{ asset('assets/module/js/select2.full.min.js') }} "></script>
<script src="{{ asset('assets/module/js/bootstrap-datepicker.min.js') }} "></script>


<script src="{{asset('assets/module/js/moment.min.js')}}"></script>

<script src="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.js')}}"></script>


<script src="{{ asset('assets/module/js/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/module/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{asset('assets/vendor/dropzone/dist/min/dropzone.min.js')}}"></script>

<script src="{{ asset('assets/js/argon.js?v=1.1.0') }} "></script>


<script src="{{ asset('assets/module/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/module/bootstrap-toastr/ui-toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/module/js/jquery.easy-autocomplete.min.js') }}"></script>


<script src="{{ asset('assets/js/demo.min.js') }} "></script>

<script>
    var dataTabelLang = {
        paginate: {
            previous: "<i class='fas fa-angle-left'>",
            next: "<i class='fas fa-angle-right'>"
        },
        lengthMenu: "{{__('Show')}} _MENU_ {{__('entries')}}",
        zeroRecords: "{{__('No data available in table')}}",
        info: "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
        infoEmpty: " ",
        search: "{{__('Search:')}}"
    }
</script>

<script src="{{ asset('assets/module/js/custom.js') }} "></script>

<script src="{{asset('assets/module/js/jquery-ui.min.js')}}"></script>


<script>
    var date_picker_locale = {
        format: 'YYYY-MM-DD',
        daysOfWeek: [
            "{{__('Sun')}}",
            "{{__('Mon')}}",
            "{{__('Tue')}}",
            "{{__('Wed')}}",
            "{{__('Thu')}}",
            "{{__('Fri')}}",
            "{{__('Sat')}}"
        ],
        monthNames: [
            "{{__('January')}}",
            "{{__('February')}}",
            "{{__('March')}}",
            "{{__('April')}}",
            "{{__('May')}}",
            "{{__('June')}}",
            "{{__('July')}}",
            "{{__('August')}}",
            "{{__('September')}}",
            "{{__('October')}}",
            "{{__('November')}}",
            "{{__('December')}}"
        ],
    };

    var calender_header = {
        today: "{{__('today')}}",
        month: '{{__('month')}}',
        week: '{{__('week')}}',
        day: '{{__('day')}}',
        list: '{{__('list')}}'
    };
</script>

@if ($message = Session::get('success'))
    <script>
        toastrs('Success', '{!! $message !!}', 'success')
    </script>
@endif

@if ($message = Session::get('error'))
    <script>toastrs('Error', '{!! $message !!}', 'error')</script>
@endif

@if ($message = Session::get('info'))
    <script>toastrs('Info', '{!! $message !!}', 'info')</script>
@endif

@stack('script-page')
