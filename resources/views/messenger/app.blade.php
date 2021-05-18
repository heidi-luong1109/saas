@extends('layouts.admin')

@push('css-page')
    <meta name="route" content="{{ $route }}">
    <meta name="url" content="{{ url('').'/'.config('chatify.path') }}" data-user="{{ Auth::user()->id }}">

    {{-- scripts --}}
    <script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
    <script src="{{ asset('js/chatify/autosize.js') }}"></script>
    {{--    <script src="{{ asset('js/app.js') }}"></script>--}}
    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

    {{-- styles --}}
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
    <link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet"/>
    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>--}}

    @include('messenger.layouts.messengerColor')
@endpush

@section('page-title')
    {{__('Messenger')}}
@endsection
@section('breadcrumb')
    <h6 class="h2 d-inline-block mb-0">{{__('Messenger')}}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Messenger')}}</li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="messenger rounded min-h-750 overflow-hidden mt-4">
                    {{-- ----------------------Users/Groups lists side---------------------- --}}
                    <div class="messenger-listView">
                        {{-- Header and search bar --}}
                        <div class="m-header">
                            <nav>
                                {{--                    <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">{{__('MESSAGES')}}</span> </a>--}}
                                {{-- header buttons --}}
                                <nav class="m-header-right">
                                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                                </nav>
                            </nav>
                            {{-- Search input --}}
                            <input type="text" class="messenger-search" placeholder="{{__('Search')}}"/>
                            {{-- Tabs --}}
                            <div class="messenger-listView-tabs">
                                <a href="#" @if($route == 'user') class="active-tab" @endif data-view="users">
                                    <span class="fas fa-clock" title="{{__('Recent')}}"></span>
                                </a>
                                <a href="#" @if($route == 'group') class="active-tab" @endif data-view="groups">
                                    <span class="fas fa-users" title="{{__('Members')}}"></span>
                                </a>
                            </div>
                        </div>
                        {{-- tabs and lists --}}
                        <div class="m-body">
                            {{-- Lists [Users/Group] --}}
                            {{-- ---------------- [ User Tab ] ---------------- --}}
                            <div class="@if($route == 'user') show @endif messenger-tab app-scroll" data-view="users">

                                {{-- Favorites --}}
                                <p class="messenger-title">{{__('Favorites')}}</p>
                                <div class="messenger-favorites app-scroll-thin"></div>

                                {{-- Saved Messages --}}
                                {!! view('messenger.layouts.listItem', ['get' => 'saved','id' => $id])->render() !!}

                                {{-- Contact --}}
                                <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);"></div>

                            </div>

                            {{-- ---------------- [ Group Tab ] ---------------- --}}
                            <div class="all_members @if($route == 'group') show @endif messenger-tab app-scroll" data-view="groups">
                                {{-- items --}}
                                <p style="text-align: center;color:grey;">{{__('Soon will be available')}}</p>
                            </div>

                            {{-- ---------------- [ Search Tab ] ---------------- --}}
                            <div class="messenger-tab app-scroll" data-view="search">
                                {{-- items --}}
                                <p class="messenger-title">{{__('Search')}}</p>
                                <div class="search-records">
                                    <p class="message-hint"><span>{{__('Type to search..')}}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ----------------------Messaging side---------------------- --}}
                    <div class="messenger-messagingView">
                        {{-- header title [conversation name] amd buttons --}}
                        <div class="m-header m-header-messaging">
                            <nav>
                                {{-- header back button, avatar and user name --}}
                                <div style="display: inline-block;">
                                    {{--                    <div style="display: inline-block;">--}}
                                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i> </a>
                                    @if(!empty(\Auth::user()->avatar))
                                        <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;background-image: url('{{ asset('/storage/uploads/avatar/'.\Auth::user()->avatar) }}');"></div>
                                    @else
                                        <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;background-image: url('{{ asset('/storage/uploads/avatar/avatar.png') }}');"></div>
                                    @endif
                                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                                </div>
                                {{-- header buttons --}}
                                <nav class="m-header-right">
                                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                                </nav>
                            </nav>
                        </div>
                        {{-- Internet connection --}}
                        <div class="internet-connection">
                            <span class="ic-connected">{{__('Connected')}}</span>
                            <span class="ic-connecting">{{__('Connecting...')}}</span>
                            <span class="ic-noInternet">{{__('No internet access')}}</span>
                        </div>
                        {{-- Messaging area --}}
                        <div class="m-body app-scroll">
                            <div class="messages">
                                <p class="message-hint" style="margin-top: calc(30% - 126.2px);"><span>{{__('Please select a chat to start messaging')}}</span></p>
                            </div>
                            {{-- Typing indicator --}}
                            <div class="typing-indicator">
                                <div class="message-card typing">
                                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                                    </p>
                                </div>
                            </div>
                            {{-- Send Message Form --}}
                            @include('messenger.layouts.sendForm')
                        </div>
                    </div>
                    {{-- ---------------------- Info side ---------------------- --}}
                    <div class="messenger-infoView app-scroll text-center">
                        {{-- nav actions --}}
                        <nav class="text-left">
                            <a href="#"><i class="fas fa-times"></i></a>
                        </nav>
                        {!! view('messenger.layouts.info')->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ---------------------- Image modal box ---------------------- --}}
    <div id="imageModalBox" class="imageModal">
        <span class="imageModal-close">&times;</span>
        <img class="imageModal-content" id="imageModalBoxSrc">
    </div>

    {{-- ---------------------- Delete Modal ---------------------- --}}
    <div class="app-modal" data-name="delete">
        <div class="app-modal-container">
            <div class="app-modal-card" data-name="delete" data-modal='0'>
                <div class="app-modal-header">{{__('Are you sure you want to delete this?')}}</div>
                <div class="app-modal-body">{{__('You can not undo this action')}}</div>
                <div class="app-modal-footer">
                    <a href="javascript:void(0)" class="app-btn cancel">{{__('Cancel')}}</a>
                    <a href="javascript:void(0)" class="app-btn a-btn-danger delete">{{__('Delete')}}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------------- Alert Modal ---------------------- --}}
    <div class="app-modal" data-name="alert">
        <div class="app-modal-container">
            <div class="app-modal-card" data-name="alert" data-modal='0'>
                <div class="app-modal-header"></div>
                <div class="app-modal-body"></div>
                <div class="app-modal-footer">
                    <a href="javascript:void(0)" class="app-btn cancel">{{__('Cancel')}}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------------- Settings Modal ---------------------- --}}
    <div class="app-modal" data-name="settings">
        <div class="app-modal-container">
            <div class="app-modal-card" data-name="settings" data-modal='0'>
                <form id="updateAvatar" action="{{ route('avatar.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="app-modal-header">{{__('Update your profile settings')}}</div>
                    <div class="app-modal-body">
                        {{-- Update profile avatar --}}
                        @if(!empty(\Auth::user()->avatar))
                            <div class="avatar av-l upload-avatar-preview" style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').\Auth::user()->avatar) }}');"></div>
                        @else
                            <div class="avatar av-l upload-avatar-preview" style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/avatar.png') }}');"></div>
                        @endif
                        <p class="upload-avatar-details"></p>
                        <label class="app-btn a-btn-primary update">
                            {{__('Upload profile photo')}}
                            <input class="upload-avatar" accept="image/*" name="avatar" type="file" style="display: none"/>
                        </label>
                        {{-- Dark/Light Mode  --}}
                        <p class="divider"></p>
                        <p class="app-modal-header">{{__('Dark Mode')}} <span class="
                        {{ Auth::user()->dark_mode > 0 ? 'fas' : 'far' }} fa-moon dark-mode-switch"
                                                                              data-mode="{{ Auth::user()->dark_mode > 0 ? 1 : 0 }}"></span></p>
                        {{-- change messenger color  --}}
                        <p class="divider"></p>
                        <p class="app-modal-header">{{__('Change')}} {{ config('chatify.name') }} {{__('Color')}}</p>
                        <div class="update-messengerColor">
                            <a href="javascript:void(0)" class="messengerColor-1"></a>
                            <a href="javascript:void(0)" class="messengerColor-2"></a>
                            <a href="javascript:void(0)" class="messengerColor-3"></a>
                            <a href="javascript:void(0)" class="messengerColor-4"></a>
                            <a href="javascript:void(0)" class="messengerColor-5"></a>
                            <br/>
                            <a href="javascript:void(0)" class="messengerColor-6"></a>
                            <a href="javascript:void(0)" class="messengerColor-7"></a>
                            <a href="javascript:void(0)" class="messengerColor-8"></a>
                            <a href="javascript:void(0)" class="messengerColor-9"></a>
                            <a href="javascript:void(0)" class="messengerColor-10"></a>
                        </div>
                    </div>
                    <div class="app-modal-footer">
                        <a href="javascript:void(0)" class="app-btn cancel">{{__('Cancel')}}</a>
                        <input type="submit" class="app-btn a-btn-success update" value="Update"/>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('messenger.layouts.modals')
@endsection

@push('script-page')
    @include('messenger.layouts.footerLinks')
@endpush
