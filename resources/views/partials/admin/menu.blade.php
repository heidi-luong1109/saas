@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
@endphp
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{route('dashboard')}}">
                <img class="img-fluid" src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" alt="">
            </a>
            <div class="ml-auto">
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link nav-toggle {{ (Request::segment(1) == 'dashboard' || Request::segment(1) == '')?'active':''}}" href="{{route('dashboard')}}">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">{{  __('Dashboard') }}</span>
                        </a>
                    </li>
                    @if(\Auth::user()->type == 'super admin')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'users')?'active':''}}" href="{{route('users.index')}}">
                                <i class="fas fa-users text-primary"></i>
                                <span class="nav-link-text">{{  __('User') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'employee' || Request::segment(1) == 'client')?'active':''}}" href="#staff" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'user' )?'true':'false'}}" aria-controls="navbar-dashboards1">
                                <i class="ni ni-single-02 text-danger"></i>
                                <span class="nav-link-text ">{{__('Staff')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'employee' || Request::segment(1) == 'client')?'show':''}}" id="staff">
                                <ul class="nav nav-sm flex-column">
                                    @if(\Auth::user()->type == 'company')
                                        <li class="nav-item {{ (Request::segment(1) == 'employee')?'active open':''}}">
                                            <a href="{{route('employee.index')}}" class="nav-link">{{__('Employee')}}</a>
                                        </li>
                                        <li class="nav-item {{ (Request::segment(1) == 'client')?'active open':''}}">
                                            <a href="{{route('client.index')}}" class="nav-link">{{__('Client')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @elseif(\Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'employee')?'active':''}}" href="{{route('employee.show',\Crypt::encrypt(\Auth::user()->id))}}">
                                <i class="ni ni-single-02 text-pink"></i>
                                <span class="nav-link-text">{{  __('My Profile') }}</span>
                            </a>
                        </li>
                    @elseif(\Auth::user()->type=='client')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'client')?'active':''}}" href="{{route('client.show',\Crypt::encrypt(\Auth::user()->id))}}">
                                <i class="ni ni-single-02 text-pink"></i>
                                <span class="nav-link-text">{{  __('My Profile') }}</span>
                            </a>
                        </li>
                    @endif

                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'attendance' || Request::segment(1) == 'holiday' || Request::segment(1) == 'leave' || Request::segment(1) == 'meeting' || Request::segment(1) == 'account-assets' || Request::segment(1) == 'document-upload'  || Request::segment(1) == 'company-policy' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'trip' || Request::segment(1) ==
                        'promotion'  || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training' || Request::segment(1) == 'bulk-attendance')
                        ?'active':''}}" href="#hr" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'attendance' || Request::segment(1) == 'holiday' || Request::segment(1) == 'leave' || Request::segment(1) == 'meeting' || Request::segment(1) == 'account-assets' || Request::segment(1) == 'document-upload'  || Request::segment(1) == 'company-policy' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) ==
                            'resignation' || Request::segment(1) == 'trip' || Request::segment(1) == 'promotion'  || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training' || Request::segment(1) == 'bulk-attendance')
                            ?'true':'false'}}"
                               aria-controls="navbar-dashboards1">
                                <i class="ni ni-collection text-info"></i>
                                <span class="nav-link-text ">{{__('HR')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'attendance' || Request::segment(1) == 'holiday' || Request::segment(1) == 'leave' || Request::segment(1) == 'meeting' || Request::segment(1) == 'account-assets' || Request::segment(1) == 'document-upload'  || Request::segment(1) == 'company-policy' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'trip' || Request::segment(1) ==
                            'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training' || Request::segment(1) == 'bulk-attendance')?'show':''}}" id="hr">
                                <ul class="nav nav-sm flex-column">
                                    <a class="nav-link" href="#performance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'attendance' || Request::segment(1) == 'bulk-attendance' )?'true':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('Attendance')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'attendance' || Request::segment(1) == 'bulk-attendance')?'show':''}}" id="performance">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'attendance')?'active open':''}}">
                                                <a href="{{route('attendance.index')}}" class="nav-link">{{__('Attendance')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'bulk-attendance')?'active open':''}}">
                                                <a href="{{route('bulk.attendance')}}" class="nav-link">{{__('Bulk Attendance')}}</a>
                                            </li>
                                        </ul>
                                    </div>


                                    <li class="nav-item {{ (Request::segment(1) == 'holiday')?'active open':''}}">
                                        <a href="{{route('holiday.index')}}" class="nav-link">{{__('Holiday')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'leave')?'active open':''}}">
                                        <a href="{{route('leave.index')}}" class="nav-link">{{__('Leave')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'meeting')?'active open':''}}">
                                        <a href="{{route('meeting.index')}}" class="nav-link">{{__('Meeting')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'account-assets')?'active open':''}}">
                                        <a href="{{route('account-assets.index')}}" class="nav-link">{{__('Asset')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'document-upload')?'active open':''}}">
                                        <a href="{{route('document-upload.index')}}" class="nav-link">{{__('Document')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'company-policy')?'active open':''}}">
                                        <a href="{{route('company-policy.index')}}" class="nav-link">{{__('Company Policy')}}</a>
                                    </li>
                                    <a class="nav-link" href="#inner-hr" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'trip' || Request::segment(1) == 'promotion'  || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' )?'true':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('HR')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'trip' || Request::segment(1) == 'promotion'  || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination')?'show':''}}" id="inner-hr">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'award')?'active open':''}}">
                                                <a href="{{route('award.index')}}" class="nav-link">{{__('Award')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'transfer')?'active open':''}}">
                                                <a href="{{route('transfer.index')}}" class="nav-link">{{__('Transfer')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'resignation')?'active open':''}}">
                                                <a href="{{route('resignation.index')}}" class="nav-link">{{__('Resignation')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'trip')?'active open':''}}">
                                                <a href="{{route('trip.index')}}" class="nav-link">{{__('Trip')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'promotion')?'active open':''}}">
                                                <a href="{{route('promotion.index')}}" class="nav-link">{{__('Promotion')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'complaint')?'active open':''}}">
                                                <a href="{{route('complaint.index')}}" class="nav-link">{{__('Complaints')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'warning')?'active open':''}}">
                                                <a href="{{route('warning.index')}}" class="nav-link">{{__('Warning')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'termination')?'active open':''}}">
                                                <a href="{{route('termination.index')}}" class="nav-link">{{__('Termination')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <a class="nav-link" href="#performance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' )?'true':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('Performance')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal')?'show':''}}" id="performance">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'indicator')?'active open':''}}">
                                                <a href="{{route('indicator.index')}}" class="nav-link">{{__('Indicator')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'appraisal')?'active open':''}}">
                                                <a href="{{route('appraisal.index')}}" class="nav-link">{{__('Appraisal')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <a class="nav-link" href="#training-list" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training' )?'show':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('Training')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'training' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training')?'show':''}}" id="training-list">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'training')?'active open':''}}">
                                                <a href="{{route('training.index')}}" class="nav-link">{{__('Training List')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'trainer')?'active open':''}}">
                                                <a href="{{route('trainer.index')}}" class="nav-link">{{__('Trainer')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee'  || \Auth::user()->type=='client')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'lead' || Request::segment(1) == 'deal' || Request::segment(1) == 'estimate')?'active':''}}" href="#presale" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'lead' || Request::segment(1) == 'deal' || Request::segment(1) == 'estimate')?'true':'false'}}" aria-controls="navbar-dashboards1">
                                <i class="ni ni-cart text-primary"></i>
                                <span class="nav-link-text ">{{__('PreSale')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'lead' || Request::segment(1) == 'deal' || Request::segment(1) == 'estimate')?'show':''}}" id="presale">
                                <ul class="nav nav-sm flex-column">
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <li class="nav-item {{ (Request::segment(1) == 'lead')?'active open':''}}">
                                            <a href="{{route('lead.index')}}" class="nav-link">{{__('Lead')}}</a>
                                        </li>
                                    @endif
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                        <li class="nav-item {{ (Request::segment(1) == 'deal')?'active open':''}}">
                                            <a href="{{route('deal.index')}}" class="nav-link">{{__('Deal')}}</a>
                                        </li>
                                    @endif
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                                        <li class="nav-item {{ (Request::segment(1) == 'estimate')?'active open':''}}">
                                            <a href="{{route('estimate.index')}}" class="nav-link">{{__('Estimation')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'project')?'active':''}}" href="#navbar-dashboards1" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'project' )?'true':'false'}}" aria-controls="navbar-dashboards1">
                                <i class="ni ni-collection text-pink"></i>
                                <span class="nav-link-text ">{{__('Project')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'project')?'show':''}}" id="navbar-dashboards1">
                                <ul class="nav nav-sm flex-column">

                                    <li class="nav-item {{ (Request::segment(1) == 'project' && Request::segment(2) != 'allTask' && Request::segment(2) != 'allTimesheet')?'active open':''}}">
                                        <a href="{{ route('project.index') }}" class="nav-link">{{__('All Project')}}</a>
                                    </li>
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <li class="nav-item {{ (Request::segment(2) == 'allTask')?'active open':''}}">
                                            <a href="{{route('project.all.task')}}" class="nav-link">{{__('Task')}}</a>
                                        </li>
                                    @endif
                                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                                        <li class="nav-item {{ (Request::segment(2) == 'allTimesheet')?'active open':''}}">
                                            <a href="{{route('project.all.timesheet')}}" class="nav-link">{{__('Timesheets')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'item')?'active':''}}" href="{{route('item.index')}}">
                                <i class="ni ni-badge text-info"></i>
                                <span class="nav-link-text">{{  __('Items') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'invoice' || Request::segment(1) == 'payment' || Request::segment(1) == 'creditNote' || Request::segment(1) == 'expense')?'active':''}}" href="#income" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'invoice' || Request::segment(1) == 'payment' || Request::segment(1) == 'creditNote' || Request::segment(1) == 'expense')?'true':'false'}}" aria-controls="income">
                                <i class="ni ni-credit-card text-primary"></i>
                                <span class="nav-link-text ">{{__('Sale')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'invoice' || Request::segment(1) == 'payment' || Request::segment(1) == 'creditNote' || Request::segment(1) == 'expense')?'show':''}}" id="income">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ (Request::segment(1) == 'invoice')?'active open':''}}">
                                        <a href="{{route('invoice.index')}}" class="nav-link">{{__('Invoices')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'payment')?'active open':''}}">
                                        <a href="{{route('payment.index')}}" class="nav-link">{{__('Payment')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'creditNote')?'active open':''}}">
                                        <a href="{{route('creditNote.index')}}" class="nav-link">{{__('Credit Notes')}}</a>
                                    </li>
                                    @if(\Auth::user()->type=='company')
                                        <li class="nav-item {{ (Request::segment(1) == 'expense')?'active open':''}}">
                                            <a href="{{route('expense.index')}}" class="nav-link">{{__('Expense')}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'contract')?'active':''}}" href="{{route('contract.index')}}">
                                <i class="ni ni-single-copy-04 text-pink"></i>
                                <span class="nav-link-text">{{  __('Contract') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'messages')?'active':''}}" href="{{ url('messages') }}">
                                <i class="ni ni-chat-round text-info"></i>
                                <span class="nav-link-text">{{  __('Messenger') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'support')?'active':''}}" href="{{route('support.index')}}">
                                <i class="ni ni-box-2 text-primary"></i>
                                <span class="nav-link-text">{{  __('Support') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'event')?'active':''}}" href="{{route('event.index')}}">
                                <i class="ni ni-calendar-grid-58 text-pink"></i>
                                <span class="nav-link-text">{{  __('Event') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'noticeBoard')?'active':''}}" href="{{route('noticeBoard.index')}}">
                                <i class="ni ni-tv-2 text-info"></i>
                                <span class="nav-link-text">{{  __('Notice Board') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'goal')?'active':''}}" href="{{route('goal.index')}}">
                                <i class="ni ni-calendar-grid-58 text-primary"></i>
                                <span class="nav-link-text">{{  __('Goal') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='client' || \Auth::user()->type=='employee')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'note')?'active':''}}" href="{{route('note.index')}}">
                                <i class="ni ni-book-bookmark text-pink"></i>
                                <span class="nav-link-text">{{  __('Notes') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'plan')?'active':''}}" href="{{route('plan.index')}}">
                                <i class="ni ni-trophy text-info"></i>
                                <span class="nav-link-text">{{  __('Plan') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='super admin')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'coupon')?'active':''}}" href="{{route('coupon.index')}}">
                                <i class="ni ni-briefcase-24 text-primary"></i>
                                <span class="nav-link-text">{{  __('Coupon') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'order')?'active':''}}" href="{{route('order.index')}}">
                                <i class="ni ni-cart"></i>
                                <span class="nav-link-text">{{  __('Order') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'email_template' || Request::segment(1) == 'email_template_lang')?'active':''}}" href="{{route('email_template.index')}}">
                                <i class="ni ni-box-2 text-pink"></i>
                                <span class="nav-link-text">{{  __('Email Template') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type=='company')
                        <li class="nav-item">
                            <a class="nav-link  {{ (Request::segment(1) == 'task-report' || Request::segment(1) == 'timelog-report'  || Request::segment(1) == 'finance-report'  || Request::segment(1) == 'income-report'  || Request::segment(1) == 'income-expense-report' || Request::segment(1) == 'leave-report'  ||
 Request::segment(1) == 'estimate-report' || Request::segment(1) == 'invoice-report' || Request::segment(1) == 'lead-report' || Request::segment(1) == 'client-report' || Request::segment(1) == 'attendance-report')?'active':''}}" href="#report" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'task-report' || Request::segment(1) == 'timelog-report' || Request::segment(1) == 'finance-report'  || Request::segment(1) == 'income-report'  || Request::segment(1) == 'income-expense-report' || Request::segment(1) == 'leave-report'  || Request::segment(1) == 'estimate-report' || Request::segment(1) == 'invoice-report' || Request::segment(1) ==
                            'lead-report' || Request::segment(1) == 'client-report' || Request::segment(1) == 'attendance-report')?'true':'false'}}" aria-controls="income">
                                <i class="ni ni-align-center text-danger"></i>
                                <span class="nav-link-text ">{{__('Report')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'task-report' || Request::segment(1) == 'timelog-report'  || Request::segment(1) == 'finance-report'  || Request::segment(1) == 'income-report'  || Request::segment(1) == 'income-expense-report' || Request::segment(1) == 'leave-report'  ||
 Request::segment(1) == 'estimate-report' || Request::segment(1) == 'invoice-report' || Request::segment(1) == 'lead-report' || Request::segment(1) == 'client-report' || Request::segment(1) == 'attendance-report')?'show':''}}" id="report">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ (Request::segment(1) == 'attendance-report')?'active open':''}}">
                                        <a href="{{route('report.attendance')}}" class="nav-link">{{__('Attendance')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'task-report')?'active open':''}}">
                                        <a href="{{route('report.task')}}" class="nav-link">{{__('Task')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'timelog-report')?'active open':''}}">
                                        <a href="{{route('report.timelog')}}" class="nav-link">{{__('Time Log')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'finance-report')?'active open':''}}">
                                        <a href="{{route('report.finance')}}" class="nav-link">{{__('Finance')}}</a>
                                    </li>

                                    <li class="nav-item {{ (Request::segment(1) == 'income-expense-report')?'active open':''}}">
                                        <a href="{{route('report.income.expense')}}" class="nav-link">{{__('Income Vs Expense')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'leave-report')?'active open':''}}">
                                        <a href="{{route('report.leave')}}" class="nav-link">{{__('Leave')}}</a>
                                    </li>

                                    <li class="nav-item {{ (Request::segment(1) == 'estimate-report')?'active open':''}}">
                                        <a href="{{route('report.estimate')}}" class="nav-link">{{__('Estimate')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'invoice-report')?'active open':''}}">
                                        <a href="{{route('report.invoice')}}" class="nav-link">{{__('Invoice')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'client-report')?'active open':''}}">
                                        <a href="{{route('report.client')}}" class="nav-link">{{__('Client')}}</a>
                                    </li>

                                    <li class="nav-item {{ (Request::segment(1) == 'lead-report')?'active open':''}}">
                                        <a href="{{route('report.lead')}}" class="nav-link">{{__('Lead')}}</a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(\Auth::user()->type=='company')
                        <li class="nav-item ">
                            <a class="nav-link {{ (Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'salaryType'  || Request::segment(1) == 'leaveType' || Request::segment(1) == 'pipeline' || Request::segment(1) == 'leadStage' || Request::segment(1) == 'projectStage' || Request::segment(1) == 'dealStage' || Request::segment(1) == 'source' || Request::segment(1) == 'label'  || Request::segment(1) == 'taxRate' || Request::segment(1) == 'unit' ||
                    Request::segment(1) == 'category' || Request::segment(1) == 'paymentMethod' || Request::segment(1) == 'contractType'  || Request::segment(1) == 'termination-type'  || Request::segment(1) == 'award-type'  || Request::segment(1) == 'training-type')
                    ?'active':''}}" href="#constant" data-toggle="collapse" role="button"
                               aria-expanded="{{ (Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'salaryType'  || Request::segment(1) == 'leaveType' || Request::segment(1) == 'pipeline' || Request::segment(1) == 'leadStage' || Request::segment(1) == 'projectStage' || Request::segment(1) == 'dealStage' || Request::segment(1) == 'source' || Request::segment(1) == 'label'  || Request::segment(1) == 'taxRate' || Request::segment(1) == 'unit' ||
                           Request::segment(1) == 'category' || Request::segment(1) == 'paymentMethod' || Request::segment(1) == 'contractType'  || Request::segment(1) == 'termination-type' || Request::segment(1) == 'award-type'  || Request::segment(1) == 'training-type')
                           ?'true':'false'}}"
                               aria-controls="navbar-dashboards1"> <i class="ni ni-atom"></i>
                                <span class="nav-link-text ">{{__('Constant')}}</span>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'salaryType'  || Request::segment(1) == 'leaveType' || Request::segment(1) == 'pipeline' || Request::segment(1) == 'leadStage' || Request::segment(1) == 'projectStage' || Request::segment(1) == 'dealStage' || Request::segment(1) == 'source' || Request::segment(1) == 'label'  || Request::segment(1) == 'taxRate' || Request::segment(1) == 'unit' ||
                        Request::segment(1) == 'category' || Request::segment(1) == 'paymentMethod' || Request::segment(1) == 'contractType'  || Request::segment(1) == 'termination-type'  || Request::segment(1) == 'award-type'  || Request::segment(1) == 'training-type')
                        ?'show':''}}" id="constant">

                                <ul class="nav nav-sm flex-column">

                                    <a class="nav-link" href="#constant-hr" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'salaryType' || Request::segment(1) == 'leaveType' || Request::segment(1) == 'award-type'|| Request::segment(1) == 'termination-type'|| Request::segment(1) == 'training-type'  )?'true':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('HR')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'salaryType' || Request::segment(1) == 'leaveType'  || Request::segment(1) == 'award-type'|| Request::segment(1) == 'termination-type'|| Request::segment(1) == 'training-type')?'show':''}}" id="constant-hr">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'department')?'active open':''}}">
                                                <a href="{{ route('department.index') }}" class="nav-link">{{__('Department')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'designation')?'active open':''}}">
                                                <a href="{{ route('designation.index') }}" class="nav-link">{{__('Designation')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'salaryType')?'active open':''}}">
                                                <a href="{{ route('salaryType.index') }}" class="nav-link">{{__('Salary Type')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'leaveType')?'active open':''}}">
                                                <a href="{{ route('leaveType.index') }}" class="nav-link">{{__('Leave Type')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'award-type')?'active open':''}}">
                                                <a href="{{ route('award-type.index') }}" class="nav-link">{{__('Award Type')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'termination-type')?'active open':''}}">
                                                <a href="{{ route('termination-type.index') }}" class="nav-link">{{__('Termination Type')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'training-type')?'active open':''}}">
                                                <a href="{{ route('training-type.index') }}" class="nav-link">{{__('Training Type')}}</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <a class="nav-link" href="#constant-presale" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'pipeline' || Request::segment(1) == 'leadStage' || Request::segment(1) == 'dealStage'  || Request::segment(1) == 'source' || Request::segment(1) == 'label')?'true':''}}" aria-controls="navbar-dashboards1">
                                        <span class="nav-link-text ">{{__('PreSale')}}</span>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'pipeline' || Request::segment(1) == 'leadStage' || Request::segment(1) == 'dealStage' || Request::segment(1) == 'source' || Request::segment(1) == 'label')?'show':''}}" id="constant-presale">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::segment(1) == 'pipeline')?'active open':''}}">
                                                <a href="{{ route('pipeline.index') }}" class="nav-link">{{__('Pipeline')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'leadStage')?'active open':''}}">
                                                <a href="{{ route('leadStage.index') }}" class="nav-link">{{__('Lead Stage')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'dealStage')?'active open':''}}">
                                                <a href="{{ route('dealStage.index') }}" class="nav-link">{{__('Deal Stage')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'source')?'active open':''}}">
                                                <a href="{{ route('source.index') }}" class="nav-link">{{__('Source')}}</a>
                                            </li>
                                            <li class="nav-item {{ (Request::segment(1) == 'label')?'active open':''}}">
                                                <a href="{{ route('label.index') }}" class="nav-link">{{__('Label')}}</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <li class="nav-item {{ (Request::segment(1) == 'projectStage')?'active open':''}}">
                                        <a href="{{ route('projectStage.index') }}" class="nav-link">{{__('Project Stage')}}</a>
                                    </li>

                                    <li class="nav-item {{ (Request::segment(1) == 'taxRate')?'active open':''}}">
                                        <a href="{{ route('taxRate.index') }}" class="nav-link">{{__('Tax Rate')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'unit')?'active open':''}}">
                                        <a href="{{ route('unit.index') }}" class="nav-link">{{__('Unit')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'category')?'active open':''}}">
                                        <a href="{{ route('category.index') }}" class="nav-link">{{__('Category')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'paymentMethod')?'active open':''}}">
                                        <a href="{{ route('paymentMethod.index') }}" class="nav-link">{{__('Payment Method')}}</a>
                                    </li>
                                    <li class="nav-item {{ (Request::segment(1) == 'contractType')?'active open':''}}">
                                        <a href="{{ route('contractType.index') }}" class="nav-link">{{__('Contract Type')}}</a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(\Auth::user()->type=='company' || \Auth::user()->type=='super admin')
                        <li class="nav-item ">
                            <a class="nav-link nav-toggle {{ (Request::segment(1) == 'settings')?'active':''}}" href="{{route('settings')}}">
                                <i class="ni ni-settings-gear-65 text-danger"></i>
                                <span class="nav-link-text">{{  __('Settings') }}</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</nav>
