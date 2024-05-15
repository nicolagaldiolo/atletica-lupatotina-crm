<div class="text-end">
    {{--@can('update', $fee)--}}
        <x-backend.buttons.update icon="fa-solid fa-coins" route='{{ route("backend.athleteFees.update", $athleteFee) }}' small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
    {{--@can('delete', $fee)--}}
        <x-backend.buttons.delete route='{{ route("backend.athleteFees.destroy", $athleteFee) }}' small="true" data_confirm='Sei sicuro?' data_method="DELETE" data_token="{{csrf_token()}}"/>
    {{--@endcan--}}
</div>
