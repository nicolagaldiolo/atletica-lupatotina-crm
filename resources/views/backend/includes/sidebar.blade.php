<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <a href="{{ config('app.url') }}">
            <img class="sidebar-brand-full" src="{{asset('img/logo.png')}}" height="60" alt="{{ app_name() }}">
            <img class="sidebar-brand-narrow" src="{{asset('img/logo.png')}}" height="60" alt="{{ app_name() }}">
            @if (!App::environment('production'))
                <span class="badge bg-success ms-1">{{ Illuminate\Support\Str::ucfirst(Illuminate\Support\Facades\App::environment()) }}</span>
            @endif
        </a>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        
        @can(App\Enums\Permissions::ViewDashboard)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('dashboard.*')) active @endif" href="{{ route('dashboard') }}">
                    <i class="nav-icon fa-solid fa-house"></i>&nbsp;@lang('Dashboard')
                </a>
            </li>
        @endcan

        @if(Auth::user()->athlete)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('athletes.*')) active @endif" href="{{ route("athletes.show", Auth::user()->athlete) }}">
                    <i class="nav-icon fa solid fa-user"></i>&nbsp;@lang('I miei dati')
                </a>
            </li>
        @endif
        
        @if (
            Gate::any(['viewAny', 'report'], App\Models\Athlete::class) || 
            Gate::check('viewAnyRace', App\Models\Race::class) ||
            Gate::check('viewAnyTrack', App\Models\Race::class)
        )
            <li class="nav-title"><a>{{ __('Gestione società') }}</a></li>
        @endif
        
        @can('viewAny', App\Models\Athlete::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('athletes.*')) active @endif" href="{{ route('athletes.index') }}">
                    <i class="nav-icon fas fa-running"></i>&nbsp;{{ __('Tesserati') }}
                </a>
            </li>
        @endcan

        @can('viewAnyRace', App\Models\Race::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('races.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Race)) active @endif" href="{{ route('races.index', App\Enums\RaceType::Race) }}">
                    <i class="nav-icon fa-solid fa-flag-checkered"></i>&nbsp;{{ __('Gare') }}
                </a>
            </li>
        @endcan

        @can('viewAnyTrack', App\Models\Race::class)
        <li class="nav-item">
            <a class="nav-link @if(Route::is('races.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Track)) active @endif" href="{{ route('races.index', App\Enums\RaceType::Track) }}">
                <i class="nav-icon fa-solid fa-ring"></i>&nbsp;{{ __('Pista') }}
            </a>
        </li>
        @endcan

        @can('report', App\Models\Athlete::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('reports.*')) active @endif" href="{{ route('reports.index') }}">
                    <i class="nav-icon fas fa-download"></i>&nbsp;{{ __('Reports') }}
                </a>
            </li>
        @endcan

        @canany(['subscribeRace', 'registerPaymentRace'], App\Models\AthleteFee::class)
            <li class="nav-title"><a>{{ __('Registrazioni Gare') }}</a></li>
        @endcanany

        @can('registerPaymentRace', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('payments.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Race)) active @endif" href="{{ route('payments.create', App\Enums\RaceType::Race) }}">
                    <i class="nav-icon fas fa-coins"></i>&nbsp;{{ __('Pagamenti Gare') }}
                </a>
            </li>
        @endcan

        @can('subscribeRace', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('races.subscription.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Race)) active @endif" href="{{ route('races.subscription.create', App\Enums\RaceType::Race) }}">
                    <i class="nav-icon fas fa-file-contract"></i>&nbsp;{{ __('Iscrizioni Gare') }}
                </a>
            </li>
        @endcan
        
        @can('registerPaymentRace', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('proceeds.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Race)) active @endif" href="{{ route('proceeds.index', App\Enums\RaceType::Race) }}">
                    <i class="nav-icon fas fa-cash-register"></i>&nbsp;{{ __('Incassi Gare') }}
                </a>
            </li>
        @endcan

        @canany(['subscribeTrack', 'registerPaymentTrack'], App\Models\AthleteFee::class)
            <li class="nav-title"><a>{{ __('Registrazioni Pista') }}</a></li>
        @endcanany

        @can('registerPaymentTrack', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('payments.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Track)) active @endif" href="{{ route('payments.create', App\Enums\RaceType::Track) }}">
                    <i class="nav-icon fas fa-coins"></i>&nbsp;{{ __('Pagamenti Pista') }}
                </a>
            </li>
        @endcan

        @can('subscribeTrack', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('races.subscription.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Track)) active @endif" href="{{ route('races.subscription.create', App\Enums\RaceType::Track) }}">
                    <i class="nav-icon fas fa-file-contract"></i>&nbsp;{{ __('Iscrizioni Pista') }}
                </a>
            </li>
        @endcan
        @can('registerPaymentTrack', App\Models\AthleteFee::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('proceeds.*') && (request()->route()->parameter('raceType') == App\Enums\RaceType::Track)) active @endif" href="{{ route('proceeds.index', App\Enums\RaceType::Track) }}">
                    <i class="nav-icon fas fa-cash-register"></i>&nbsp;{{ __('Incassi Pista') }}
                </a>
            </li>
        @endcan
        
        @can('viewAny', App\Models\Article::class)
            <li class="nav-title"><a>{{ __('Magazzino') }}</a></li>
            <li class="nav-item">
                <a class="nav-link @if(Route::is('articles.*')) active @endif" href="{{ route('articles.index') }}">
                    <i class="nav-icon fa-solid fa-shirt"></i>&nbsp;{{ __('Abbigliamento') }}
                </a>
            </li>
        @endcan

        @if(Gate::check('viewAny', App\Models\User::class) || Gate::check('viewAny', App\Models\Role::class))
            <li class="nav-title"><a>{{ __('Amministrazione') }}</a></li>
        @endif
        
        @can('viewAny', App\Models\User::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('users.*')) active @endif" href="{{ route('users.index') }}">
                    <i class="nav-icon fa-solid fa-user-group"></i>&nbsp;{{ __('Users') }}
                </a>
            </li>
        @endcan

        @can('viewAny', App\Models\Role::class)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('roles.*')) active @endif" href="{{ route('roles.index') }}">
                    <i class="nav-icon fa-solid fa-user-shield"></i>&nbsp;{{ __('Ruoli') }}
                </a>
            </li>
        @endcan

        @can(App\Enums\Permissions::RunMaintenance)
            <li class="nav-item">
                <a class="nav-link @if(Route::is('tasks.*')) active @endif" href="{{ route('tasks.index') }}">
                    <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>&nbsp;{{ __('Manutenzione') }}
                </a>
            </li>
        @endcan

    </ul>
</div>
