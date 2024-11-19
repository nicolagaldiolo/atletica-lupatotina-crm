@php
    $layout = $layout ?? null;
@endphp
@if($layout == 'nav')
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-pills me-auto mb-2 mb-lg-0">
                    
                    @can('viewAny', App\Models\Athlete::class)
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.index')) active @endif" aria-current="page" href="{{ route("athletes.index") }}">
                                <i class="nav-icon fas fa-running"></i>
                                {{ __('Elenco atleti') }}
                            </a>
                        </li>
                    @endcan

                    @can('update', $athlete)
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.edit')) active @endif" aria-current="page" href="{{ route("athletes.edit", $athlete) }}">
                                <i class="nav-icon fas fa-edit"></i>
                                {{ __('Anagrafica') }}
                            </a>
                        </li>
                    @elsecan('view', $athlete)
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.show')) active @endif" aria-current="page" href="{{ route("athletes.show", $athlete) }}">
                                <i class="nav-icon fas fa-edit"></i>
                                {{ __('Anagrafica') }}
                            </a>
                        </li>
                    @endcan
                    
                    @can('viewAny', [App\Models\Certificate::class, $athlete])
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.certificates.*')) active @endif" aria-current="page" href="{{ route("athletes.certificates.index", $athlete) }}">
                                <i class="fas fa-briefcase-medical"></i>
                                {{ __('Dati sanitari') }}
                            </a>
                        </li>
                    @endcan

                    @if (Gate::any(['subscribe', 'registerPayment', 'viewAny'], [App\Models\AthleteFee::class, $athlete]))
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.races.*')) active @endif" aria-current="page" href="{{ route("athletes.races.index", $athlete) }}">
                                <i class="fa-solid fa-flag-checkered"></i>
                                {{ __('Iscrizioni') }}
                            </a>
                        </li>
                    @endif

                    @can('viewAny', [App\Models\Voucher::class, $athlete])
                        <li class="nav-item">
                            <a class="nav-link @if(Route::is('athletes.vouchers.*')) active @endif" aria-current="page" href="{{ route("athletes.vouchers.index", $athlete) }}">
                                <i class="fas fa-tags"></i>
                                {{ __('Voucher') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
    </nav>
@else
    <div class="text-end">
        @can('invite', $athlete)
            <x-backend.buttons.edit route='{{ route("invite", $athlete) }}' small="true" icon="fas fa-link" title="{{ __('Invita utente') }}" />
        @endcan
        @can('update', $athlete)
            <x-backend.buttons.edit route='{{ route("athletes.edit", $athlete) }}' small="true" title="{{ __('Anagrafica') }}" />
        @endcan
        @can('viewAny', [App\Models\Certificate::class, $athlete])
            <x-backend.buttons.edit route='{{ route("athletes.certificates.index", $athlete) }}' small="true" icon="fas fa-briefcase-medical" title="{{ __('Certificati medici') }}"/>
        @endcan
        
        @if (Gate::any(['subscribe', 'registerPayment'], App\Models\AthleteFee::class))
            <x-backend.buttons.edit route='{{ route("athletes.races.index", $athlete) }}' small="true" icon="fa-solid fa-flag-checkered" title="{{ __('Iscrizioni') }}"/>
        @endif

        @can('viewAny', App\Models\Voucher::class)
            <x-backend.buttons.edit route='{{ route("athletes.vouchers.index", $athlete) }}' small="true" icon="fas fa-tags" title="{{ __('Voucher') }}"/>
        @endcan
        @can('delete', $athlete)
            <x-backend.buttons.delete route='{{ route("athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" title="{{ __('Elimina') }}" data_token="{{csrf_token()}}"/>
        @endcan
    </div>
@endif