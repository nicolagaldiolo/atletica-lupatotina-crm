<div class="text-end">
    @can('view', $race)
        <x-backend.buttons.show route='{{ route("races.trashed.show", $race) }}' title="{{__('Show')}}" small="true" />
    @endcan
    @can('restore', $race)
        <x-backend.buttons.restore route='{{ route("races.restore", $race) }}' small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}" />
    @endcan
</div>
