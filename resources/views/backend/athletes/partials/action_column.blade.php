<div class="text-end">
    @can('update', $athlete)
        <x-backend.buttons.edit route='{{ route("invite", $athlete) }}' small="true" icon="fas fa-link" title="invita" />
        <x-backend.buttons.edit route='{{ route("athletes.races.index", $athlete) }}' small="true" />
    @endcan
</div>
