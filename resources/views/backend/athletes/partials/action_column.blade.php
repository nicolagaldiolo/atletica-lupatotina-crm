@php
    $layout = $layout ?? null;
@endphp
@if($layout == 'nav')
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-coreui-toggle="collapse" data-coreui-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @can('viewAny', App\Models\Athlete::class)
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route("athletes.index") }}">
                                <i class="far fa-times-circle"></i>
                                {{ __('Chiudi') }}
                            </a>
                        </li>
                    @endcan
                    
                    @can('update', $athlete)
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route("athletes.edit", $athlete) }}">{{ __('Anagrafica') }}</a>
                        </li>
                    @endcan
                    
                    @can('viewAny', [App\Models\Certificate::class, $athlete])
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route("athletes.certificates.index", $athlete) }}">{{ __('Dati sanitari') }}</a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("athletes.races.index", $athlete) }}">{{ __('Iscrizioni') }}</a>
                    </li>

                    @can('viewAny', App\Models\Voucher::class)
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route("athletes.vouchers.index", $athlete) }}">{{ __('Voucher') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
    </nav>
@else
    <div class="text-end">
        @can('invite', $athlete)
            <x-backend.buttons.edit route='{{ route("invite", $athlete) }}' small="true" icon="fas fa-link" title="invita" />
        @endcan
        @can('update', $athlete)
            <x-backend.buttons.edit route='{{ route("athletes.edit", $athlete) }}' small="true" />
        @endcan
        @can('viewAny', [App\Models\Certificate::class, $athlete])
            <x-backend.buttons.edit route='{{ route("athletes.certificates.index", $athlete) }}' small="true" icon="fas fa-briefcase-medical"/>
        @endcan
        <x-backend.buttons.edit route='{{ route("athletes.races.index", $athlete) }}' small="true" icon="fa-solid fa-flag-checkered"/>
        @can('viewAny', App\Models\Voucher::class)
        <x-backend.buttons.edit route='{{ route("athletes.vouchers.index", $athlete) }}' small="true" icon="fas fa-tags"/>
        @endcan
        @can('delete', $athlete)
            <x-backend.buttons.delete route='{{ route("athletes.destroy", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
        @endcan
    </div>
@endif