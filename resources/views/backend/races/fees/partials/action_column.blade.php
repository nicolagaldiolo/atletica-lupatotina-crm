<div class="text-end">
    @canany(['updateRace', 'updateTrack'], $fee)
        <x-backend.buttons.edit route='{{ route("races.fees.edit", [$race->type, $race, $fee]) }}' small="true" title="{{ __('Modifica') }}"/>
    @endcanany
    @canany(['deleteRace', 'deleteTrack'], $fee)
        <x-backend.buttons.delete route='{{ route("races.fees.destroy", [$race->type, $race, $fee]) }}' small="true" title="{{ __('Elimina') }}" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcanany
</div>
