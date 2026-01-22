<div class="text-end">

    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $race)--}}
        <x-backend.buttons.edit route='{{ route("seasons.edit", $season) }}' small="true" title="{{ __('Modifica') }}"/>
    {{--@endcan--}}

    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'reportRace' : (($race->type == App\Enums\RaceType::Track) ? 'reportTrack' : false)), $race)--}}
        <x-backend.buttons.edit route='{{ route("seasons.orders.index", $season) }}' icon="fas fa-coins" title="{{ 'Ordini' }}" small="true" />
    {{--@endcan--}}

    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $race)--}}
        <x-backend.buttons.delete route='{{ route("seasons.destroy", $season) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
    
</div>