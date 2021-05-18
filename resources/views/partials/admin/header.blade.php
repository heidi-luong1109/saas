@php
    $users=\Auth::user();
    $profile=asset(Storage::url('uploads/avatar/'));
    $logo=asset(Storage::url('uploads/logo/'));
    $currantLang = $users->currentLanguage();
      $languages=Utility::languages();
@endphp

<nav class="navbar navbar-top navbar-expand navbar-light bg-secondary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">

                    <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{__('Language')}}">
                        <i class="fa fa-flag"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right language-dropdown">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{__('Choose Language')}}</h6>
                        </div>
                        @foreach($languages as $language)
                            <a href="{{route('change.language',$language)}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                <span> {{Str::upper($language)}}</span>
                            </a>
                        @endforeach
                        @if(\Auth::user()->type=='super admin')
                            <a href="{{route('manage.language',[$currantLang])}}" class="dropdown-item">
                                <span>   {{ __('Create & Customize') }}</span>
                            </a>
                        @endif
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                          <span class="avatar avatar-sm rounded-circle">
                            <img src="{{(!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')}}">
                          </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">{{$users->name}} </span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{__('Welcome!')}}</h6>
                        </div>
                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>{{__('My profile')}}</span>
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="dropdown-item">
                            <i class="ni ni-button-power"></i>
                            <span>{{__('Logout')}}</span>
                        </a>
                        <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>


