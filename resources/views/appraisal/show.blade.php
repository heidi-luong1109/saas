<div class="row">
    <div class="col-md-12">
        <div class="info">
            <strong>{{__('Branch')}} : </strong>
            <span>{{ !empty($appraisal->branches)?$appraisal->branches->name:'' }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Employee')}} : </strong>
            <span>{{!empty($appraisal->employees)?$appraisal->employees->name:'' }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Appraisal Date')}} : </strong>
            <span>{{$appraisal->appraisal_date }}</span>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="my-3">
        <h3 class="indicator-title">{{__('Technical Competencies')}}</h3>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Customer Experience')}} : </strong>
            <span>{{ __(\App\Appraisal::$technical[$appraisal->customer_experience]) }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Marketing')}} : </strong>
            <span>{{ __(\App\Indicator::$technical[$appraisal->marketing]) }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Administration')}} : </strong>
            <span>{{ __(\App\Indicator::$technical[$appraisal->administration]) }}</span>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="my-3">
        <h3 class="indicator-title">{{__('Organizational Competencies')}}</h3>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Professionalism')}} : </strong>
            <span>{{ __(\App\Indicator::$technical[$appraisal->professionalism]) }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Integrity')}} : </strong>
            <span>{{ __(\App\Indicator::$technical[$appraisal->integrity]) }}</span>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="info font-style">
            <strong>{{__('Attendance')}} : </strong>
            <span>{{ __(\App\Indicator::$technical[$appraisal->attendance]) }}</span>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="my-3">
        <h3 class="indicator-title">{{__('Remark')}}</h3>
    </div>
    <div class="col-md-12 mt-3">
        <p>{{$appraisal->remark }}</p>
    </div>
</div>

