@extends('layouts.admin')
@section('page-title')
    {{ $emailTemplate->name }}
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/module/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('assets/module/summernote/summernote-bs4.js')}}"></script>
@endpush
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0"> {{ $emailTemplate->name }}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('email_template.index')}}">{{__('Email Templates')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{ $emailTemplate->name }}</li>
        </ol>
    </nav>

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{Form::model($emailTemplate, array('route' => array('email_template.update', $emailTemplate->id), 'method' => 'PUT')) }}
                        <div class="row">
                            <div class="form-group col-md-5">
                                {{Form::label('name',__('Name'))}}
                                {{Form::text('name',null,array('class'=>'form-control font-style','disabled'=>'disabled'))}}
                            </div>
                            <div class="form-group col-md-5">
                                {{Form::label('from',__('From'))}}
                                {{Form::text('from',null,array('class'=>'form-control font-style','required'=>'required'))}}
                            </div>
                            {{Form::hidden('lang',$currEmailTempLang->lang,array('class'=>''))}}
                            <div class="form-group col-md-2 my-auto">
                                {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="nav-wrapper email-nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='lead_assign')?'active':''}}" id="lead-tab" data-toggle="tab" href="#lead" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Lead')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='deal_assign')?'active':''}}  " id="deal-tab" data-toggle="tab" href="#deal" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Deal')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='send_estimation')?'active':''}} " id="estimation-tab" data-toggle="tab" href="#estimation" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Estimation')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='create_project' || $emailTemplate->slug=='project_assign' || $emailTemplate->slug=='project_finished')?'active':''}} " id="project-tab" data-toggle="tab" href="#project" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Project')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='task_assign')?'active':''}} " id="task-tab" data-toggle="tab" href="#task" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Project Task')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='send_invoice'||  $emailTemplate->slug=='invoice_payment_recored')?'active':''}} " id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Invoice')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='credit_note')?'active':''}} " id="credit_note-tab" data-toggle="tab" href="#credit_note" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Credit Note')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='create_support')?'active':''}} " id="support-tab" data-toggle="tab" href="#support" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Support')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='create_contract')?'active':''}} " id="contract-tab" data-toggle="tab" href="#contract" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Contract')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 {{($emailTemplate->slug=='create_user')?'active':''}}" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{__('Other')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="shadow email-header-type">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade {{($emailTemplate->slug=='lead_assign')?'show active':''}}" id="lead" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Lead Name')}} : <span class="pull-right text-primary">{lead_name}</span></p>
                                        <p class="col-4">{{__('Lead Email')}} : <span class="pull-right text-primary">{lead_email}</span></p>
                                        <p class="col-4">{{__('Lead Subject')}} : <span class="pull-right text-primary">{lead_subject}</span></p>
                                        <p class="col-4">{{__('Lead Pipeline')}} : <span class="pull-right text-primary">{lead_pipeline}</span></p>
                                        <p class="col-4">{{__('Lead Stage')}} : <span class="pull-right text-primary">{lead_stage}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='deal_assign')?'show active':''}}" id="deal" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Deal Name')}} : <span class="pull-right text-primary">{deal_name}</span></p>
                                        <p class="col-4">{{__('Deal Pipeline')}} : <span class="pull-right text-primary">{deal_pipeline}</span></p>
                                        <p class="col-4">{{__('Deal Stage')}} : <span class="pull-right text-primary">{deal_stage}</span></p>
                                        <p class="col-4">{{__('Deal Status')}} : <span class="pull-right text-primary">{deal_status}</span></p>
                                        <p class="col-4">{{__('Deal Price')}} : <span class="pull-right text-primary">{deal_price}</span></p>
                                        <p class="col-4">{{__('Deal Stage')}} : <span class="pull-right text-primary">{deal_stage}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='send_estimation')?'show active':''}}" id="estimation" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Estimation Name')}} : <span class="pull-right text-primary">{estimation_id}</span></p>
                                        <p class="col-4">{{__('Estimation Client')}} : <span class="pull-right text-primary">{estimation_client}</span></p>
                                        <p class="col-4">{{__('Estimation Category')}} : <span class="pull-right text-primary">{estimation_category}</span></p>
                                        <p class="col-4">{{__('Estimation Issue Date')}} : <span class="pull-right text-primary">{estimation_issue_date}</span></p>
                                        <p class="col-4">{{__('Estimation Expiry Date')}} : <span class="pull-right text-primary">{estimation_expiry_date}</span></p>
                                        <p class="col-4">{{__('Estimation Status')}} : <span class="pull-right text-primary">{estimation_status}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='create_project' || $emailTemplate->slug=='project_assign' || $emailTemplate->slug=='project_finished')?'show active':''}}" id="project" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Project Title')}} : <span class="pull-right text-primary">{project_title}</span></p>
                                        <p class="col-4">{{__('Project Category')}} : <span class="pull-right text-primary">{project_category}</span></p>
                                        <p class="col-4">{{__('Project Price')}} : <span class="pull-right text-primary">{project_price}</span></p>
                                        <p class="col-4">{{__('Project Client')}} : <span class="pull-right text-primary">{project_client}</span></p>
                                        <p class="col-4">{{__('Project Assign User')}} : <span class="pull-right text-primary">{project_assign_user}</span></p>
                                        <p class="col-4">{{__('Project Start Date')}} : <span class="pull-right text-primary">{project_start_date}</span></p>
                                        <p class="col-4">{{__('Project Due Date')}} : <span class="pull-right text-primary">{project_due_date}</span></p>
                                        <p class="col-4">{{__('Project Lead')}} : <span class="pull-right text-primary">{project_lead}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='task_assign')?'show active':''}}" id="task" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Project')}} : <span class="pull-right text-primary">{project}</span></p>
                                        <p class="col-4">{{__('Task Title')}} : <span class="pull-right text-primary">{task_title}</span></p>
                                        <p class="col-4">{{__('Task Priority')}} : <span class="pull-right text-primary">{task_priority}</span></p>
                                        <p class="col-4">{{__('Task Start Date')}} : <span class="pull-right text-primary">{task_start_date}</span></p>
                                        <p class="col-4">{{__('Task Due Date')}} : <span class="pull-right text-primary">{task_due_date}</span></p>
                                        <p class="col-4">{{__('Task Stage')}} : <span class="pull-right text-primary">{task_stage}</span></p>
                                        <p class="col-4">{{__('Task Assign User')}} : <span class="pull-right text-primary">{task_assign_user}</span></p>
                                        <p class="col-4">{{__('Task Description')}} : <span class="pull-right text-primary">{task_description}</span></p>
                                    </div>
                                </div>

                                <div class="tab-pane fade {{($emailTemplate->slug=='send_invoice' || $emailTemplate->slug == 'invoice_payment_recored')?'show active':''}}" id="invoice" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Invoice Number')}} : <span class="pull-right text-primary">{invoice_id}</span></p>
                                        <p class="col-4">{{__('Invoice Client')}} : <span class="pull-right text-primary">{invoice_client}</span></p>
                                        <p class="col-4">{{__('Invoice Issue Date')}} : <span class="pull-right text-primary">{invoice_issue_date}</span></p>
                                        <p class="col-4">{{__('Invoice Due Date')}} : <span class="pull-right text-primary">{invoice_due_date}</span></p>
                                        <p class="col-4">{{__('Invoice Status')}} : <span class="pull-right text-primary">{invoice_status}</span></p>
                                        <p class="col-4">{{__('Invoice Total')}} : <span class="pull-right text-primary">{invoice_total}</span></p>
                                        <p class="col-4">{{__('Invoice Sub Total')}} : <span class="pull-right text-primary">{invoice_sub_total}</span></p>
                                        <p class="col-4">{{__('Invoice Due Amount')}} : <span class="pull-right text-primary">{invoice_due_amount}</span></p>
                                        <p class="col-4">{{__('Invoice Status')}} : <span class="pull-right text-primary">{invoice_status}</span></p>
                                        <p class="col-4">{{__('Invoice Payment Recorded Total')}} : <span class="pull-right text-primary">{payment_total}</span></p>
                                        <p class="col-4">{{__('Invoice Payment Recorded Date')}} : <span class="pull-right text-primary">{payment_date}</span></p>

                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='credit_note')?'show active':''}}" id="credit_note" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Invoice Number')}} : <span class="pull-right text-primary">{invoice_id}</span></p>
                                        <p class="col-4">{{__('Date')}} : <span class="pull-right text-primary">{credit_note_date}</span></p>
                                        <p class="col-4">{{__('Invoice Client')}} : <span class="pull-right text-primary">{invoice_client}</span></p>
                                        <p class="col-4">{{__('Amount')}} : <span class="pull-right text-primary">{credit_amount}</span></p>
                                        <p class="col-4">{{__('Description')}} : <span class="pull-right text-primary">{credit_description}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='create_support')?'show active':''}}" id="support" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Ticket Title')}} : <span class="pull-right text-primary">{support_title}</span></p>
                                        <p class="col-4">{{__('Ticket Assign User')}} : <span class="pull-right text-primary">{assign_user}</span></p>
                                        <p class="col-4">{{__('Ticket Priority')}} : <span class="pull-right text-primary">{support_priority}</span></p>
                                        <p class="col-4">{{__('Ticket End Date')}} : <span class="pull-right text-primary">{support_end_date}</span></p>
                                        <p class="col-4">{{__('Ticket Description')}} : <span class="pull-right text-primary">{support_description}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='create_contract')?'show active':''}}" id="contract" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('Contract Subject')}} : <span class="pull-right text-primary">{contract_subject}</span></p>
                                        <p class="col-4">{{__('Contract Client')}} : <span class="pull-right text-primary">{contract_client}</span></p>
                                        <p class="col-4">{{__('Contract Value')}} : <span class="pull-right text-primary">{contract_value}</span></p>
                                        <p class="col-4">{{__('Contract Start Date')}} : <span class="pull-right text-primary">{contract_start_date}</span></p>
                                        <p class="col-4">{{__('Contract End Date')}} : <span class="pull-right text-primary">{contract_end_date}</span></p>
                                        <p class="col-4">{{__('Contract Description')}} : <span class="pull-right text-primary">{contract_description}</span></p>
                                    </div>
                                </div>
                                <div class="tab-pane fade {{($emailTemplate->slug=='create_user')?'show active':''}}" id="other" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="row">
                                        <p class="col-4">{{__('App Name')}} : <span class="pull-right text-primary">{app_name}</span></p>
                                        <p class="col-4">{{__('Company Name')}} : <span class="pull-right text-primary">{company_name}</span></p>
                                        <p class="col-4">{{__('App Url')}} : <span class="pull-right text-primary">{app_url}</span></p>
                                        <p class="col-4">{{__('Email')}} : <span class="pull-right text-primary">{email}</span></p>
                                        <p class="col-4">{{__('Password')}} : <span class="pull-right text-primary">{password}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="language-wrap">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 language-list-wrap">
                                    <div class="language-list">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                            @foreach($languages as $lang)
                                                <li class="nav-item">
                                                    <a href="{{route('manage.email.language',[$emailTemplate->id,$lang])}}" class="nav-link {{($currEmailTempLang->lang == $lang)?'active':''}}">{{Str::upper($lang)}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-12 language-form-wrap">
                                    {{Form::model($currEmailTempLang, array('route' => array('store.email.language',$currEmailTempLang->parent_id), 'method' => 'PUT')) }}
                                    <div class="row">
                                        <div class="form-group col-12">
                                            {{Form::label('subject',__('Subject'))}}
                                            {{Form::text('subject',null,array('class'=>'form-control font-style','required'=>'required'))}}
                                        </div>
                                        <div class="form-group col-12">
                                            {{Form::label('content',__('Email Message'))}}
                                            {{Form::textarea('content',$currEmailTempLang->content,array('class'=>'summernote-simple','required'=>'required','rows'=>5))}}
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        {{Form::hidden('lang',null)}}
                                        {{Form::submit(__('Save'),array('class'=>'btn btn-primary'))}}
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


