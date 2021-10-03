<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="pl-5 pr-5">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="sr-only">{!! trans('titles.toggleNav') !!}</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Left Side Of Navbar --}}
            <ul class="navbar-nav mr-auto">
           
        <li>
        <a class="navbar-brand" href="{{ url('/') }}">
            Bahisrator
        </a>

        </li>
                @role('admin')
                <li class="nav-item dropdown">
                    
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {!! trans('titles.adminDropdownNav') !!}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
             
                        <a class="dropdown-item {{ (Request::is('server_setting')) ? 'active' : null }}" href="{{ route('server_setting') }}">
                           Server Status
                        </a>
                        <div class="dropdown-divider"></div>
                               <a class="dropdown-item {{ (Request::is('roles') || Request::is('permissions')) ? 'active' : null }}" href="{{ route('laravelroles::roles.index') }}">
                            {!! trans('titles.laravelroles') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}" href="{{ url('/users') }}">
                            {!! trans('titles.adminUserList') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('users/create') ? 'active' : null }}" href="{{ url('/users/create') }}">
                            {!! trans('titles.adminNewUser') !!}
                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item {{ Request::is('logs') ? 'active' : null }}" href="{{ url('/logs') }}">
                            {!! trans('titles.adminLogs') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('activity') ? 'active' : null }}" href="{{ url('/activity') }}">
                            {!! trans('titles.adminActivity') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('logging') ? 'active' : null }}" href="{{ url('/logging') }}">
                            Server Logger
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('phpinfo') ? 'active' : null }}" href="{{ url('/phpinfo') }}">
                            {!! trans('titles.adminPHP') !!}
                        </a>


                    </div>
                </li>
                @endrole
                @role('admin')
                <a class="nav-link " href="/codes" role="button" aria-haspopup="true" aria-expanded="false">
                    Codes
                </a>
                @endrole
                @auth
                <a class="nav-link " href="/bet_companies" role="button" aria-haspopup="true" aria-expanded="false">
                    Bet Companies
                </a>
                @endauth
                @role('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDomainsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Domains
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDomainsDropdown">
                        <a class="dropdown-item {{ (Request::is('un_used_domain_index') || Request::is('permissions')) ? 'active' : null }}" href="{{ route('un_used_domain_index') }}">
                            Unused Domains
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ (Request::is('movable_and_used_domain_index') || Request::is('permissions')) ? 'active' : null }}" href="{{ route('movable_and_used_domain_index') }}">
                            Running Domains
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ (Request::is('domains') || Request::is('permissions')) ? 'active' : null }}" href="{{ url('/domains') }}">
                            Unmovable Running Domains
                        </a>
                        <div class="dropdown-divider"></div>



                    </div>
                </li>
                @endrole

                @auth

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDomainsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Contents
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDomainsDropdown">
                        
                        <a class="nav-link " href="/contents" role="button" aria-haspopup="true" aria-expanded="false">
                        Content List
                </a>
                        <div class="dropdown-divider"></div>
                        <a class="nav-link " href="/websites" role="button" aria-haspopup="true" aria-expanded="false">
                    Website Picker
                </a>
                        <div class="dropdown-divider"></div>
                        


                    </div>
                </li>
                @endauth
                



            </ul>
            {{-- Right Side Of Navbar --}}
            <ul class="navbar-nav ml-auto">
                {{-- Authentication Links --}}
                @guest
                <li><a class="nav-link" href="{{ route('login') }}">{{ trans('titles.login') }}</a></li>

                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        @if ((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
                        <img src="{{ Auth::user()->profile->avatar }}" alt="{{ Auth::user()->name }}" class="user-avatar-nav">
                        @else
                        <div class="user-avatar-nav"></div>
                        @endif
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item {{ Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'active' : null }}" href="{{ url('/profile/'.Auth::user()->name) }}">
                            {!! trans('titles.profile') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>