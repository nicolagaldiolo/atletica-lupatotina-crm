<div class="text-end">
    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("backend.races.athletes", $race) }}' small="true" />
    @endcan
</div>
