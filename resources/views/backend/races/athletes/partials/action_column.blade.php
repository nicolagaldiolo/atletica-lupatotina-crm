<div class="text-end">
    {{--@can('update', $fee)--}}
        {{--<x-backend.buttons.edit route='{{ route("backend.races.athletes.edit", [$race_id, $athlete_id]) }}' small="true" />--}}
    {{--@endcan--}}
    {{--@can('delete', $fee)--}}
        <x-backend.buttons.delete route='{{ route("backend.athleteFees.destroy", [$race,$athleteFee]) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
</div>
