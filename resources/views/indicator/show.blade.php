
<div class="row">
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Department')}} </h4>  {{ !empty($indicator->departments)?$indicator->departments->name:'' }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Designation')}} </h4> {{ !empty($indicator->designations)?$indicator->designations->name:'' }}
        </div>
    </div>
    <div class="col-md-12">
        <hr class="my-3">
        <h3 class="indicator-title">{{__('Technical Competencies')}}</h3>
    </div>
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Customer Experience')}} </h4>  {{ __(\App\Indicator::$technical[$indicator->customer_experience]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Marketing')}} </h4>{{ __(\App\Indicator::$technical[$indicator->marketing]) }}
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Administration')}} </h4>  {{ __(\App\Indicator::$technical[$indicator->administration]) }}
        </div>
    </div>
    <div class="col-md-12">
        <hr class="my-3">
        <h3 class="indicator-title">{{__('Organizational Competencies')}}</h3>
    </div>
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Professionalism')}} </h4>  {{ __(\App\Indicator::$technical[$indicator->professionalism]) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Integrity')}} </h4>  {{ __(\App\Indicator::$technical[$indicator->integrity]) }}
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <h4 class="mb-0">{{__('Attendance')}} </h4>  {{ __(\App\Indicator::$technical[$indicator->attendance]) }}
        </div>
    </div>
</div>

