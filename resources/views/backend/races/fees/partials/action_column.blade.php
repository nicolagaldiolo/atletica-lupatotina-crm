<div class="text-end">
    @can('update', $fee)
        <x-backend.buttons.edit route='{{ route("backend.races.fees.edit", [$race, $fee]) }}' small="true" />
    @endcan
    {{--@can('delete', $fee)
        <x-backend.buttons.delete route='{{ route("backend.races.fees.destroy", [$race, $fee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    @endcan--}}
</div>
