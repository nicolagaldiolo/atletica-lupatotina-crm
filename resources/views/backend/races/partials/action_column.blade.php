<div class="text-end">
    <x-backend.buttons.show route='{{ route("backend.races.fees.index", $race) }}' icon="fa-solid fa-coins" small="true" count="{{ $race->fees_count }}" />
    <x-backend.buttons.show route='{{ route("backend.races.athletes", $race) }}' icon="fas fa-running" small="true" count="{{ $race->athlete_fee_count }}"/>
    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("backend.races.edit", $race) }}' small="true" />
    @endcan
    @can('delete', $race)
        <x-backend.buttons.delete route='{{ route("backend.races.destroy", $race) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan
</div>
