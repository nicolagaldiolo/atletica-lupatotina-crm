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
                @can('viewAny', App\Models\Race::class)
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("races.index") }}">
                            <i class="far fa-times-circle"></i>
                            {{ __('Chiudi') }}
                        </a>
                    </li>
                @endcan
                @can('update', $race)
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("races.edit", $race) }}">{{ __('Anagrafica') }}</a>
                    </li>
                @endcan
                @can('viewAny', App\Models\Fee::class)
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("races.fees.index", $race) }}">{{ __('Quote') }}</a>
                    </li>
                @endcan
                @can('report', $race)
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route("races.athletes", $race) }}">{{ __('Iscrizioni') }}</a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</nav>
@else
<div class="text-end">

    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("races.edit", $race) }}' small="true" />
    @endcan
    @can('viewAny', App\Models\Fee::class)
        <x-backend.buttons.edit route='{{ route("races.fees.index", $race) }}' icon="fa fa-coins" title="{{ __('Quote') }}" small="true" />
    @endcan
    @can('report', $race)
        <x-backend.buttons.edit route='{{ route("races.athletes", $race) }}' icon="fas fa-running" title="{{ 'Iscrizioni' }}" small="true" />
    @endcan
    @can('delete', $race)
        <x-backend.buttons.delete route='{{ route("races.destroy", $race) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
    
</div>
@endif