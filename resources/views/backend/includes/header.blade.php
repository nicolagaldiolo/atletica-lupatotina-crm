<header class="header header-sticky mb-2">
    <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a class="header-brand d-sm-none" href="{{ config('app.url') }}">
            <img class="sidebar-brand-full" src="{{asset('img/logo.png')}}" height="46" alt="{{ app_name() }}">
        </a>
        
        <ul class="header-nav">
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="{{ config('app.url') }}" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        <img class="avatar-img" src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-2">
                        <div class="fw-semibold">{{ __('Account') }}</div>
                    </div>

                    <a class="dropdown-item" href="{{route('users.edit', Auth::user()->id)}}">
                        <i class="fa-solid fa-user me-2"></i>&nbsp;{{ Auth::user()->name }}
                    </a>
                    
                    <div class="dropdown-divider"></div>

                    <div class="dropdown-header bg-light py-2"><strong>@lang('Settings')</strong></div>

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>&nbsp;
                        @lang('Logout')
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
                </div>
            </li>
        </ul>
    </div>

    <div class="header-divider"></div>

    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <div class="d-flex align-items-center">
                @yield('before-breadcrumbs')
                <ol class="breadcrumb my-0">
                    @yield('breadcrumbs')
                </ol>
            </div>
        </nav>
        <div class="d-flex flex-row float-end">
            @yield('secondary-nav')
        </div>
    </div>
</header>
