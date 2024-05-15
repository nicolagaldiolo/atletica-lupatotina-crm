<div class="text-end">
    @can('update', $race)
        <x-backend.buttons.edit route='{{ route("backend.races.edit", $race) }}' small="true" />
    @endcan
</div>
