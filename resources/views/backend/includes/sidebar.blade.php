<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <a href="/">
            <img class="sidebar-brand-full" src="{{asset('img/logo.png')}}" height="46" alt="{{ app_name() }}">
            <img class="sidebar-brand-narrow" src="{{asset('img/logo.png')}}" height="46" alt="{{ app_name() }}">
        </a>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        
        @can(App\Enums\Permissions::ViewDashboard)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="nav-icon fa-solid fa-cubes"></i>&nbsp;@lang('Dashboard')
                </a>
            </li>
        @endcan

        @if(Auth::user()->athlete)
            <li class="nav-item">
                <a class="nav-link" href="{{ route("athletes.edit", Auth::user()->athlete) }}">
                    <i class="nav-icon fas fa-running"></i>&nbsp;@lang('I miei dati')
                </a>
            </li>
        @endif
        
        @if(
            Gate::check('viewAny', App\Models\Athlete::class) || 
            Gate::check('viewAny', App\Models\Race::class) ||
            Gate::check('report', App\Models\Athlete::class)
        )
            <li class="nav-title"><a>{{ __('Gestione società') }}</a></li>
        @endif
        
        @can('viewAny', App\Models\Athlete::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('athletes.index') }}">
                    <i class="nav-icon fas fa-running"></i>&nbsp;{{ __('Tesserati') }}
                </a>
            </li>
        @endcan

        @can('viewAny', App\Models\Race::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('races.index') }}">
                    <i class="nav-icon fa-solid fa-flag-checkered"></i>&nbsp;{{ __('Gare') }}
                </a>
            </li>
        @endcan

        @can('report', App\Models\Athlete::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('athletes.reports') }}">
                    <i class="nav-icon fas fa-download"></i>&nbsp;{{ __('Situazione soci') }}
                </a>
            </li>
        @endcan
        
        @if(
            Gate::check('subscribe', App\Models\Race::class) || 
            Gate::check('registerPayment', App\Models\Race::class)
        )
            <li class="nav-title"><a>{{ __('Registrazioni') }}</a></li>
        @endif

        @can('registerPayment', App\Models\Race::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('payments.create') }}">
                    <i class="nav-icon fas fa-coins"></i>&nbsp;{{ __('Pagamenti') }}
                </a>
            </li>
        @endcan

        @can('subscribe', App\Models\Race::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('races.subscription.create') }}">
                    <i class="nav-icon fas fa-file-contract"></i>&nbsp;{{ __('Iscrizioni') }}
                </a>
            </li>
        @endcan
        
        @if(
            Gate::check('viewAny', App\Models\User::class) || 
            Gate::check('viewAny', App\Models\Role::class)
        )
            <li class="nav-title"><a>{{ __('Amministrazione') }}</a></li>
        @endif
        
        @can('viewAny', App\Models\User::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="nav-icon fa-solid fa-user-group"></i>&nbsp;{{ __('Users') }}
                </a>
            </li>
        @endcan

        @can('viewAny', App\Models\Role::class)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <i class="nav-icon fa-solid fa-user-shield"></i>&nbsp;{{ __('Ruoli') }}
                </a>
            </li>
        @endcan

    </ul>

    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
