<div class="text-end">
    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
    <x-backend.buttons.show route='{{ route("seasons.orders.show", [$season, $order]) }}' small="true" title="{{ __('Visualizza') }}"/>    
    <x-backend.buttons.edit route='{{ route("seasons.orders.edit", [$season, $order]) }}' small="true" title="{{ __('Modifica') }}"/>
    {{--@endcan--}}
    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $fee)--}}
        <x-backend.buttons.delete route='{{ route("seasons.orders.destroy", [$season, $order]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
</div>
