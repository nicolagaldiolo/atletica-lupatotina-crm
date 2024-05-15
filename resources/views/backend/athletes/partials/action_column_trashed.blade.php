<div class="text-end">
    @can('view', $athlete)
        <x-backend.buttons.show route='{{ route("backend.athletes.trashed.show", $athlete) }}' title="{{__('Show')}}" small="true" />
    @endcan
    @can('restore', $athlete)
        <x-backend.buttons.restore route='{{ route("backend.athletes.restore", $athlete) }}' small="true" data_confirm='Sei sicuro?' data_method="PATCH" data_token="{{csrf_token()}}" />
    @endcan
</div>
