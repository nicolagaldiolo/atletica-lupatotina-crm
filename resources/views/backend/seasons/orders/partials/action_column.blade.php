<div class="text-end">
    {{--@can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)--}}
    {{--
    
    --}}
    <x-backend.buttons.show route='{{ route("seasons.orders.edit", [$season, $order]) }}' icon="fas fa-edit" small="true" title="{{ __('Modifica') }}"/>    
</div>
