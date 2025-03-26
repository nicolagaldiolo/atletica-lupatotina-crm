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
                @can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Race::class)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.index')) active @endif" aria-current="page" href="{{ route("races.index", $race->type) }}">
                            <i class="fa-solid fa-flag-checkered"></i>
                            {{ __('Elenco gare') }}
                        </a>
                    </li>
                @endcan
                @can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $race)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.edit')) active @endif" aria-current="page" href="{{ route("races.edit", [$race->type, $race]) }}">
                            <i class="fa-solid fa-edit"></i>
                            {{ __('Anagrafica') }}
                        </a>
                    </li>
                @endcan
                @can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.fees.*')) active @endif" aria-current="page" href="{{ route("races.fees.index", [$race->type, $race]) }}">
                            <i class="fa fa-coins"></i>
                            {{ __('Quote') }}
                        </a>
                    </li>
                @endcan
                @can((($race->type == App\Enums\RaceType::Race) ? 'reportRace' : (($race->type == App\Enums\RaceType::Track) ? 'reportTrack' : false)), $race)
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('races.athletes')) active @endif" aria-current="page" href="{{ route("races.athletes", [$race->type, $race]) }}">
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
    
    @can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $race)
        <x-backend.buttons.edit route='{{ route("races.edit", [$race->type, $race]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan

    @can((($race->type == App\Enums\RaceType::Race) ? 'viewAnyRace' : (($race->type == App\Enums\RaceType::Track) ? 'viewAnyTrack' : false)), App\Models\Fee::class)
        <x-backend.buttons.edit route='{{ route("races.fees.index", [$race->type, $race]) }}' icon="fa fa-coins" title="{{ __('Quote') }}" small="true" />
    @endcan

    @can((($race->type == App\Enums\RaceType::Race) ? 'reportRace' : (($race->type == App\Enums\RaceType::Track) ? 'reportTrack' : false)), $race)
        <x-backend.buttons.edit route='{{ route("races.athletes", [$race->type, $race]) }}' icon="fas fa-running" title="{{ 'Iscrizioni' }}" small="true" />
    @endcan

    @can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $race)
        <x-backend.buttons.delete route='{{ route("races.destroy", [$race->type, $race]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
    
</div>
@endif