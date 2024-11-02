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
                @can('viewAny', App\Models\Race::class)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.index')) active @endif" aria-current="page" href="{{ route("races.index", $race) }}">
                            <i class="fa-solid fa-flag-checkered"></i>
                            {{ __('Elenco gare') }}
                        </a>
                    </li>
                @endcan
                @can('update', $race)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.edit')) active @endif" aria-current="page" href="{{ route("races.edit", $race) }}">
                            <i class="fa-solid fa-edit"></i>
                            {{ __('Anagrafica') }}
                        </a>
                    </li>
                @endcan
                @can('viewAny', App\Models\Fee::class)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.fees.*')) active @endif" aria-current="page" href="{{ route("races.fees.index", $race) }}">
                            <i class="fa fa-coins"></i>
                            {{ __('Quote') }}
                        </a>
                    </li>
                @endcan
                @can('report', $race)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.athletes')) active @endif" aria-current="page" href="{{ route("races.athletes", $race) }}">
                            <i class="fas fa-running"></i>
                            {{ __('Iscrizioni') }}
                        </a>
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