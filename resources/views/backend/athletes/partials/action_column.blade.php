<div class="text-end">
    @can('update', $athlete)
        <x-backend.buttons.edit route='{{ route("backend.athletes.edit", $athlete) }}' small="true" />
    @endcan
</div>
