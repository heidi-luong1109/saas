@extends('layouts.admin')
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
    $company_favicon=Utility::getValByName('company_favicon');
$lang=\App\Utility::getValByName('default_language');
@endphp
@section('page-title')
    {{__('Settings')}}
@endsection
@push('css-page')
    <link href="{{ asset('assets/module/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .card-body {
            padding: 9px 24px;
            flex: 1 1 auto;
        }
    </style>
@endpush

@push('script-page')
    <script src="{{ asset('assets/module/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script>
        $(document).on("change", "select[name='estimate_template'], input[name='estimate_color']", function () {
            var template = $("select[name='estimate_template']").val();
            var color = $("input[name='estimate_color']:checked").val();
            $('#estimate_frame').attr('src', '{{url('/estimate/preview')}}/' + template + '/' + color);
        });
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{url('/invoice/preview')}}/' + template + '/' + color);
        });

    </script>

@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Settings')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs">
                                    @if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company')
                                        <li><a class="active" data-toggle="tab" href="#business-setting">{{__('Business Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#company-setting">{{__('Company Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='super admin')
                                        <li><a data-toggle="tab" href="#email-setting">{{__('Email Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#system-setting">{{__('System Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#estimate-template-setting">{{__('Estimate Print Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#invoice-template-setting">{{__('Invoice Print Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='super admin')
                                        <li><a data-toggle="tab" href="#pusher-template-setting">{{__('Pusher Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='super admin')
                                        <li><a data-toggle="tab" href="#payment-template-setting">{{__('Payment Setting')}}</a></li>
                                    @endif
                                    @if(\Auth::user()->type=='company')
                                        <li><a data-toggle="tab" href="#company-payment-template-setting">{{__('Payment Setting')}}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            @if(\Auth::user()->type=='super admin')
                                <div id="business-setting" class="tab-pane fade in active show">
                                    {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 290px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 290px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="logo" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 text-center">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_small_logo) && !empty($company_small_logo)?$company_small_logo:'small_logo.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 340px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="small_logo" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 340px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="favicon" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('logo')
                                        <div class="row mt-10 mb-10">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                        </div>
                                        @enderror
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {{Form::label('title_text',__('Title Text')) }}
                                                {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                                @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                             <strong class="text-danger">{{ $message }}</strong>
                                             </span>
                                                @enderror
                                            </div>
                                            @if(\Auth::user()->type=='super admin')
                                                <div class="form-group col-md-6">
                                                    {{Form::label('footer_text',__('Footer Text')) }}
                                                    {{Form::text('footer_text',null,array('class'=>'form-control','placeholder'=>__('Footer Text')))}}
                                                    @error('footer_text')
                                                    <span class="invalid-footer_text" role="alert">
                                                         <strong class="text-danger">{{ $message }}</strong>
                                                         </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('default_language',__('Default Language')) }}
                                                    <div class="changeLanguage">
                                                        <select name="default_language" id="default_language" class="form-control custom-select">
                                                            @foreach(\App\Utility::languages() as $language)
                                                                <option @if($lang == $language) selected @endif value="{{$language }}">{{Str::upper($language)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                    {{Form::close()}}
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div id="business-setting" class="tab-pane fade in active show">
                                    {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 290px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 290px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="company_logo" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 text-center">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_small_logo) && !empty($company_small_logo)?$company_small_logo:'small_logo.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 340px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="company_small_logo" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                        <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" alt=""></div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 340px; max-height: 150px; line-height: 10px;"></div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new">  {{__('Select image')}}  </span>
                                                            <span class="fileinput-exists"> {{__('Change')}} </span>
                                                            <input type="hidden">
                                                            <input type="file" name="company_favicon" id="logo">
                                                        </span>
                                                        <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> {{__('Remove')}} </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('logo')
                                        <div class="row mt-10 mb-10">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                        </div>
                                        @enderror
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {{Form::label('title_text',__('Title Text')) }}
                                                {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                                @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                             <strong class="text-danger">{{ $message }}</strong>
                                             </span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                    {{Form::close()}}
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div id="company-setting" class="tab-pane fade">
                                    <div class="row">
                                        {{Form::model($settings,array('route'=>'company.setting','method'=>'post'))}}
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_name *',__('Company Name *')) }}
                                                    {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_name')
                                                    <span class="invalid-company_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_address',__('Address')) }}
                                                    {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_address')
                                                    <span class="invalid-company_address" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_city',__('City')) }}
                                                    {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_city')
                                                    <span class="invalid-company_city" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_state',__('State')) }}
                                                    {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_state')
                                                    <span class="invalid-company_state" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_zipcode',__('Zip/Post Code')) }}
                                                    {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                                    @error('company_zipcode')
                                                    <span class="invalid-company_zipcode" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group  col-md-6">
                                                    {{Form::label('company_country',__('Country')) }}
                                                    {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_country')
                                                    <span class="invalid-company_country" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_telephone',__('Telephone')) }}
                                                    {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                                    @error('company_telephone')
                                                    <span class="invalid-company_telephone" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_email',__('System Email *')) }}
                                                    {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                                    @error('company_email')
                                                    <span class="invalid-company_email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_email_from_name',__('Email (From Name) *')) }}
                                                    {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                                                    @error('company_email_from_name')
                                                    <span class="invalid-company_email_from_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('registration_number',__('Company Registration Number *')) }}
                                                    {{Form::text('registration_number',null,array('class'=>'form-control'))}}
                                                    @error('registration_number')
                                                    <span class="invalid-registration_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('vat_number',__('VAT Number *')) }}
                                                    {{Form::text('vat_number',null,array('class'=>'form-control'))}}
                                                    @error('vat_number')
                                                    <span class="invalid-vat_number" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    {{Form::label('timezone',__('Timezone'))}}
                                                    <select type="text" name="timezone" class="form-control custom-select" id="timezone">
                                                        <option value="">{{__('Select Timezone')}}</option>
                                                        @foreach($timezones as $k=>$timezone)
                                                            <option value="{{$k}}" {{(env('TIMEZONE')==$k)?'selected':''}}>{{$timezone}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_start_time',__('Company Start Time *')) }}
                                                    {{Form::time('company_start_time',null,array('class'=>'form-control'))}}
                                                    @error('company_start_time')
                                                    <span class="invalid-company_start_time" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('company_end_time',__('Company End Time *')) }}
                                                    {{Form::time('company_end_time',null,array('class'=>'form-control'))}}
                                                    @error('company_end_time')
                                                    <span class="invalid-company_end_time" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='super admin')
                                <div id="email-setting" class="tab-pane fade">
                                    <div class="card-body">
                                        <div class="row">
                                            {{Form::open(array('route'=>'email.setting','method'=>'post'))}}
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_driver',__('Mail Driver')) }}
                                                    {{Form::text('mail_driver',env('MAIL_DRIVER'),array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))}}
                                                    @error('mail_driver')
                                                    <span class="invalid-mail_driver" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_host',__('Mail Host')) }}
                                                    {{Form::text('mail_host',env('MAIL_HOST'),array('class'=>'form-control ','placeholder'=>__('Enter Mail Driver')))}}
                                                    @error('mail_host')
                                                    <span class="invalid-mail_driver" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_port',__('Mail Port')) }}
                                                    {{Form::text('mail_port',env('MAIL_PORT'),array('class'=>'form-control','placeholder'=>__('Enter Mail Port')))}}
                                                    @error('mail_port')
                                                    <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_username',__('Mail Username')) }}
                                                    {{Form::text('mail_username',env('MAIL_USERNAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))}}
                                                    @error('mail_username')
                                                    <span class="invalid-mail_username" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_password',__('Mail Password')) }}
                                                    {{Form::text('mail_password',env('MAIL_PASSWORD'),array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))}}
                                                    @error('mail_password')
                                                    <span class="invalid-mail_password" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_encryption',__('Mail Encryption')) }}
                                                    {{Form::text('mail_encryption',env('MAIL_ENCRYPTION'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                                    @error('mail_encryption')
                                                    <span class="invalid-mail_encryption" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_from_address',__('Mail From Address')) }}
                                                    {{Form::text('mail_from_address',env('MAIL_FROM_ADDRESS'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))}}
                                                    @error('mail_from_address')
                                                    <span class="invalid-mail_from_address" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{Form::label('mail_from_name',__('Mail From Name')) }}
                                                    {{Form::text('mail_from_name',env('MAIL_FROM_NAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                                    @error('mail_from_name')
                                                    <span class="invalid-mail_from_name" role="alert">
                                                 <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                            </div>
                                            <div class="card-footer text-right">
                                                <a href="#" data-url="{{route('test.mail' )}}" data-ajax-popup="true" data-title="{{__('Send Test Mail')}}" class="btn btn-primary btn-action mr-1 float-right">
                                                    {{__('Send Test Mail')}}
                                                </a>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div id="system-setting" class="tab-pane fade">
                                    <div class="card-body">
                                        <div class="row">
                                            {{Form::model($settings,array('route'=>'system.setting','method'=>'post'))}}
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{Form::label('site_currency',__('Currency *')) }}
                                                    {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                                                    @error('site_currency')
                                                    <span class="invalid-site_currency" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('site_currency_symbol',__('Currency Symbol *')) }}
                                                    {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                                    @error('site_currency_symbol')
                                                    <span class="invalid-site_currency_symbol" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="custom-control custom-radio mb-3">

                                                                    <input type="radio" id="customRadio5" name="site_currency_symbol_position" value="pre" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                                    <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="custom-control custom-radio mb-3">
                                                                    <input type="radio" id="customRadio6" name="site_currency_symbol_position" value="post" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                                    <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
                                                    <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                                        <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                                        <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                                        <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                                        <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
                                                    <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                                        <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                                        <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                                        <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('client_prefix',__('Client Prefix')) }}
                                                    {{Form::text('client_prefix',null,array('class'=>'form-control'))}}
                                                    @error('client_prefix')
                                                    <span class="invalid-client_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('employee_prefix',__('Employee Prefix')) }}
                                                    {{Form::text('employee_prefix',null,array('class'=>'form-control'))}}
                                                    @error('employee_prefix')
                                                    <span class="invalid-employee_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('estimate_prefix',__('Estimate Prefix')) }}
                                                    {{Form::text('estimate_prefix',null,array('class'=>'form-control'))}}
                                                    @error('estimate_prefix')
                                                    <span class="invalid-estimate_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('invoice_prefix',__('Invoice Prefix')) }}
                                                    {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                                    @error('invoice_prefix')
                                                    <span class="invalid-invoice_prefix" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('footer_title',__('Estimate/Invoice Footer Title')) }}
                                                    {{Form::text('footer_title',null,array('class'=>'form-control'))}}
                                                    @error('footer_title')
                                                    <span class="invalid-footer_title" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('footer_notes',__('Estimate/Invoice Footer Notes')) }}
                                                    {{Form::textarea('footer_notes', null, ['class'=>'form-control','rows'=>'4'])}}
                                                    @error('footer_notes')
                                                    <span class="invalid-footer_notes" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="text-right">
                                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                                    </div>
                                                </div>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div id="estimate-template-setting" class="tab-pane fade">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <form id="setting-form" method="post" action="{{route('estimate.template.setting')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="address">{{__('Estimate Template')}}</label>
                                                        <select class="form-control" name="estimate_template">
                                                            @foreach(Utility::templateData()['templates'] as $key => $template)
                                                                <option value="{{$key}}" {{(isset($settings['estimate_template']) && $settings['estimate_template'] == $key) ? 'selected' : ''}}> {{$template}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('Color Input')}}</label>
                                                        <div class="row gutters-xs">
                                                            @foreach(Utility::templateData()['colors'] as $key => $color)
                                                                <div class="col-auto">
                                                                    <label class="colorinput">
                                                                        <input name="estimate_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['estimate_color']) && $settings['estimate_color'] == $color) ? 'checked' : ''}}>
                                                                        <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary">
                                                        {{__('Save')}}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-10">
                                                @if(isset($settings['estimate_template']) && isset($settings['estimate_color']))
                                                    <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0" src="{{route('estimate.preview',[$settings['estimate_template'],$settings['estimate_color']])}}"></iframe>
                                                @else
                                                    <iframe id="estimate_frame" class="w-100 h-1220" frameborder="0" src="{{route('estimate.preview',['template1','fffff'])}}"></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div id="invoice-template-setting" class="tab-pane fade">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <form id="setting-form" method="post" action="{{route('invoice.template.setting')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="address">{{__('Invoice Template')}}</label>
                                                        <select class="form-control" name="invoice_template">
                                                            @foreach(Utility::templateData()['templates'] as $key => $template)
                                                                <option value="{{$key}}" {{(isset($settings['estimate_template']) && $settings['estimate_template'] == $key) ? 'selected' : ''}}> {{$template}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('Color Input')}}</label>
                                                        <div class="row gutters-xs">
                                                            @foreach(Utility::templateData()['colors'] as $key => $color)
                                                                <div class="col-auto">
                                                                    <label class="colorinput">
                                                                        <input name="invoice_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($settings['invoice_color']) && $settings['invoice_color'] == $color) ? 'checked' : ''}}>
                                                                        <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary">
                                                        {{__('Save')}}
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-md-10">
                                                @if(isset($settings['invoice_template']) && isset($settings['invoice_color']))
                                                    <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0" src="{{route('invoice.preview',[$settings['invoice_template'],$settings['invoice_color']])}}"></iframe>
                                                @else
                                                    <iframe id="invoice_frame" class="w-100 h-1220" frameborder="0" src="{{route('invoice.preview',['template1','fffff'])}}"></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='super admin')
                                <div id="pusher-template-setting" class="tab-pane fade">
                                    {{Form::model($settings,array('route'=>'pusher.setting','method'=>'post'))}}
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {{Form::label('pusher_app_id *',__('Pusher App Id *')) }}
                                                    {{Form::text('pusher_app_id',env('PUSHER_APP_ID'),array('class'=>'form-control font-style'))}}
                                                    @error('pusher_app_id')
                                                    <span class="invalid-pusher_app_id" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('pusher_app_key',__('Pusher App Key')) }}
                                                    {{Form::text('pusher_app_key',env('PUSHER_APP_KEY'),array('class'=>'form-control font-style'))}}
                                                    @error('pusher_app_key')
                                                    <span class="invalid-pusher_app_key" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('pusher_app_secret',__('Pusher App Secret')) }}
                                                    {{Form::text('pusher_app_secret',env('PUSHER_APP_SECRET'),array('class'=>'form-control font-style'))}}
                                                    @error('pusher_app_secret')
                                                    <span class="invalid-pusher_app_secret" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('pusher_app_cluster',__('Pusher App Cluster')) }}
                                                    {{Form::text('pusher_app_cluster',env('PUSHER_APP_CLUSTER'),array('class'=>'form-control font-style'))}}
                                                    @error('pusher_app_cluster')
                                                    <span class="invalid-pusher_app_cluster" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                    {{Form::close()}}
                                </div>
                            @endif
                            @if(\Auth::user()->type=='super admin')
                                <div class="tab-pane fade" id="payment-template-setting" role="tabpanel">
                                    <div class="row">
                                        <div class="card-body">
                                            {{Form::open(array('route'=>'payment.setting','method'=>'post'))}}
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <h5>{{__("This detail will use for collect payment on invoice from clients. On invoice client will find out pay now button based on your below configuration.")}}</h5>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('currency_symbol',__('Currency Symbol *')) }}
                                                    {{Form::text('currency_symbol',env('CURRENCY_SYMBOL'),array('class'=>'form-control','required'))}}
                                                    @error('currency_symbol')
                                                    <span class="invalid-currency_symbol" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('currency',__('Currency *')) }}
                                                    {{Form::text('currency',env('CURRENCY'),array('class'=>'form-control font-style','required'))}}
                                                    <small> {{__('Note: Add currency code as per three-letter ISO code.')}}<br> <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a></small> <br>
                                                    @error('currency')
                                                    <span class="invalid-currency" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <h2>{{ __('Stripe') }}</h2>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {{Form::label('is_enable_stripe',__('Enable Stripe'), ['class' => 'custom-toggle-btn']) }}
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="enable_stripe" {{ env('ENABLE_STRIPE') == 'on' ? 'checked="checked"' : '' }}>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('stripe_key',__('Stripe Key')) }}
                                                    {{Form::text('stripe_key',env('STRIPE_KEY'),['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])}}
                                                    @error('stripe_key')
                                                    <span class="invalid-stripe_key" role="alert">
                                                                 <strong class="text-danger">{{ $message }}</strong>
                                                             </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('stripe_secret',__('Stripe Secret')) }}
                                                    {{Form::text('stripe_secret',env('STRIPE_SECRET'),['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])}}
                                                    @error('stripe_secret')
                                                    <span class="invalid-stripe_secret" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <h2>{{ __('Paypal') }}</h2>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {{Form::label('enable_stripe',__('Enable Paypal'), ['class' => 'custom-toggle-btn']) }}
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="enable_paypal" class="custom-switch-input" {{ env('ENABLE_PAYPAL') == 'on' ? 'checked="checked"' : '' }}>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group form-group col-md-12">
                                                    <label for="paypal_mode" class="custom-radio">{{ __('Paypal Mode') }}</label>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="paypal_mode" value="sandbox" class="custom-control-input" {{ env('PAYPAL_MODE') == '' || env('PAYPAL_MODE') == 'sandbox' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label" for="customRadioInline1">{{ __('Sandbox') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline ">
                                                        <input type="radio" id="customRadioInline2" name="paypal_mode" value="live" class="custom-control-input" {{ env('PAYPAL_MODE') == 'live' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label" for="customRadioInline2">{{ __('Live') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paypal_client_id">{{ __('Client ID') }}</label>
                                                    <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{env('PAYPAL_CLIENT_ID')}}" placeholder="{{ __('Client ID') }}"/>
                                                    @if ($errors->has('paypal_client_id'))
                                                        <span class="invalid-feedback d-block">
                                                        {{ $errors->first('paypal_client_id') }}
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                    <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{env('PAYPAL_SECRET_KEY')}}" placeholder="{{ __('Secret Key') }}"/>
                                                    @if ($errors->has('paypal_secret_key'))
                                                        <span class="invalid-feedback d-block">
                                                        {{ $errors->first('paypal_secret_key') }}
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card-footer text-right">
                                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                                    </div>
                                                </div>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->type=='company')
                                <div class="tab-pane fade" id="company-payment-template-setting" role="tabpanel">
                                    <div class="row">
                                        <div class="card-body">
                                            {{Form::model($settings,['route'=>'company.payment.setting', 'method'=>'POST'])}}
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <h5>{{__("This detail will use for collect payment on invoice from clients. On invoice client will find out pay now button based on your below configuration.")}}</h5>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <h2>{{ __('Stripe') }}</h2>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {{Form::label('is_enable_stripe',__('Enable Stripe'), ['class' => 'custom-toggle-btn']) }}
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="enable_stripe" {{ isset($settings['enable_stripe']) && $settings['enable_stripe'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('stripe_key',__('Stripe Key')) }}
                                                    {{Form::text('stripe_key',(isset($settings['stripe_key'])?$settings['stripe_key']:''),['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])}}
                                                    @error('stripe_key')
                                                    <span class="invalid-stripe_key" role="alert">
                                                         <strong class="text-danger">{{ $message }}</strong>
                                                     </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('stripe_secret',__('Stripe Secret')) }}
                                                    {{Form::text('stripe_secret',(isset($settings['stripe_secret'])?$settings['stripe_secret']:''),['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])}}
                                                    @error('stripe_secret')
                                                    <span class="invalid-stripe_secret" role="alert">
                                                     <strong class="text-danger">{{ $message }}</strong>
                                                 </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <h2>{{ __('Paypal') }}</h2>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {{Form::label('enable_stripe',__('Enable Paypal'), ['class' => 'custom-toggle-btn']) }}
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="enable_paypal" class="custom-switch-input" {{  isset($settings['enable_paypal']) && $settings['enable_paypal'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group form-group col-md-12">
                                                    <label for="paypal_mode" class="custom-radio">{{ __('Paypal Mode') }}</label>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="customRadioInline1" name="paypal_mode" value="sandbox" class="custom-control-input"{{ isset($settings['paypal_mode']) &&  $settings['paypal_mode'] == '' || isset($settings['paypal_mode']) && $settings['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label" for="customRadioInline1">{{ __('Sandbox') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline ">
                                                        <input type="radio" id="customRadioInline2" name="paypal_mode" value="live" class="custom-control-input" {{ isset($settings['paypal_mode']) &&  $settings['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label" for="customRadioInline2">{{ __('Live') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paypal_client_id">{{ __('Client ID') }}</label>
                                                    <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{isset($settings['paypal_client_id'])?$settings['paypal_client_id']:''}}" placeholder="{{ __('Client ID') }}"/>
                                                    @if ($errors->has('paypal_client_id'))
                                                        <span class="invalid-feedback d-block">
                                                        {{ $errors->first('paypal_client_id') }}
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                    <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{isset($settings['paypal_secret_key'])?$settings['paypal_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                    @if ($errors->has('paypal_secret_key'))
                                                        <span class="invalid-feedback d-block">
                                                        {{ $errors->first('paypal_secret_key') }}
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card-footer text-right">
                                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                                                    </div>
                                                </div>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

