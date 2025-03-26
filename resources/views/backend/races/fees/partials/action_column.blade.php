<div class="text-end">
    @can((($race->type == App\Enums\RaceType::Race) ? 'updateRace' : (($race->type == App\Enums\RaceType::Track) ? 'updateTrack' : false)), $fee)
        <x-backend.buttons.edit route='{{ route("races.fees.edit", [$race->type, $race, $fee]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcan
    @can((($race->type == App\Enums\RaceType::Race) ? 'deleteRace' : (($race->type == App\Enums\RaceType::Track) ? 'deleteTrack' : false)), $fee)
        <x-backend.buttons.delete route='{{ route("races.fees.destroy", [$race->type, $race, $fee]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
