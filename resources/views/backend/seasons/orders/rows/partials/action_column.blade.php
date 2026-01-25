<div class="text-end">
    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
    {{--
    
    --}}
    {{--
    <x-backend.buttons.show route='{{ route("seasons.orders.show", [$season, $order]) }}' icon="fas fa-bars" small="true" title="{{ __('Visualizza') }}"/>    
    --}}
</div>
